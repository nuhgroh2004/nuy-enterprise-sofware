<?php

namespace App\Http\Controllers;

use App\Models\BomComponent;
use App\Models\BomHeader;
use App\Models\BomSubstitute;
use App\Models\BomVersion;
use App\Models\Company;
use App\Models\Plant;
use App\Models\ProductionProcess;
use App\Models\Product;
use App\Models\RoutingHeader;
use App\Models\RoutingVersion;
use App\Models\UnitOfMeasure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomApiController extends Controller
{
    /* ============================================================
     | HELPER — case-insensitive search (SQLite + PostgreSQL safe)
     ============================================================ */

    private function ilike($query, $column, $value): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $query->whereRaw("LOWER({$column}) LIKE LOWER(?)", [$value]);
        } else {
            $query->where($column, 'ilike', $value);
        }
    }

    /* ============================================================
     | API — List BOMs
     ============================================================ */

    public function index(Request $request): JsonResponse
    {
        $query = BomHeader::with([
            'product', 'plant', 'activeVersion',
        ])->withCount('versions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $this->ilike($q, 'code', "%{$search}%");
                $this->ilike($q->orWhere, 'name', "%{$search}%");
                $q->orWhereHas('product', function ($pq) use ($search) {
                    $this->ilike($pq, 'code', "%{$search}%");
                    $this->ilike($pq->orWhere, 'name', "%{$search}%");
                });
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('activeVersion', function ($q) use ($request) {
                $q->where('approval_state', $request->status);
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->is_active);
        }

        $sortField = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['code', 'name', 'updated_at', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('updated_at');
        }

        $boms = $query->paginate($request->get('per_page', 20));

        $stats = [
            'total' => BomHeader::count(),
            'active' => BomHeader::where('is_active', true)->count(),
            'draft' => BomHeader::whereHas('versions', fn($q) => $q->where('approval_state', 'draft'))->count(),
            'approved' => BomHeader::whereHas('versions', fn($q) => $q->where('approval_state', 'approved')->where('is_default', true))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $boms,
            'stats' => $stats,
        ]);
    }

    /* ============================================================
     | API — Show Single BOM
     ============================================================ */

    public function show(BomHeader $bom): JsonResponse
    {
        $bom->load([
            'company', 'plant', 'product.uom', 'productionProcess',
            'versions.components.product.uom',
            'versions.components.substitutes.product',
            'versions.routingVersion',
            'versions.outputUom',
            'versions.submittedBy',
            'versions.approvedByUser',
            'versions',
        ]);

        return response()->json([
            'success' => true,
            'data' => $bom,
        ]);
    }

    /* ============================================================
     | API — Store BOM
     ============================================================ */

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plant_id' => 'nullable|exists:plants,id',
            'product_id' => 'required|exists:products,id',
            'code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'production_process_id' => 'nullable|exists:production_processes,id',
        ]);

        $exists = BomHeader::where('company_id', $validated['company_id'])
            ->where('code', $validated['code'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'BOM code already exists in this company.',
            ], 422);
        }

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $bom = DB::transaction(function () use ($validated) {
            $bom = BomHeader::create($validated);

            $bom->auditLogs()->create([
                'company_id' => $bom->company_id,
                'user_id' => auth()->id(),
                'event' => 'created',
                'new_values' => $bom->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $bom;
        });

        return response()->json([
            'success' => true,
            'message' => 'BOM created successfully.',
            'data' => $bom->load(['company', 'plant', 'product']),
        ], 201);
    }

    /* ============================================================
     | API — Update BOM
     ============================================================ */

    public function update(Request $request, BomHeader $bom): JsonResponse
    {
        $validated = $request->validate([
            'plant_id' => 'nullable|exists:plants,id',
            'product_id' => 'sometimes|required|exists:products,id',
            'code' => 'sometimes|required|string|max:50',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'production_process_id' => 'nullable|exists:production_processes,id',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['code']) && $validated['code'] !== $bom->code) {
            $exists = BomHeader::where('company_id', $bom->company_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $bom->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM code already exists in this company.',
                ], 422);
            }
        }

        $oldValues = $bom->toArray();
        $validated['updated_by'] = auth()->id();

        $bom = DB::transaction(function () use ($bom, $validated, $oldValues) {
            $bom->update($validated);

            $newValues = $bom->toArray();
            $changed = [];
            foreach ($newValues as $key => $value) {
                if (array_key_exists($key, $oldValues) && $oldValues[$key] != $value) {
                    $changed[$key] = ['old' => $oldValues[$key], 'new' => $value];
                }
            }

            if (!empty($changed)) {
                $bom->auditLogs()->create([
                    'company_id' => $bom->company_id,
                    'user_id' => auth()->id(),
                    'event' => 'updated',
                    'old_values' => $changed,
                    'new_values' => $newValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            return $bom->load(['company', 'plant', 'product']);
        });

        return response()->json([
            'success' => true,
            'message' => 'BOM updated successfully.',
            'data' => $bom,
        ]);
    }

    /* ============================================================
     | API — Archive BOM
     ============================================================ */

    public function archive(BomHeader $bom): JsonResponse
    {
        $hasApprovedVersion = $bom->versions()
            ->where('approval_state', 'approved')
            ->exists();

        if ($hasApprovedVersion) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive BOM with approved versions. Please deactivate versions first.',
            ], 422);
        }

        $hasProductionOrder = $bom->versions()
            ->whereHas('productionOrders', function ($q) {
                $q->whereIn('status', ['draft', 'planned', 'released', 'in_progress']);
            })->exists();

        if ($hasProductionOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive BOM used in open production orders.',
            ], 422);
        }

        $oldValues = $bom->toArray();

        $bom->update(['is_active' => false]);
        $bom->delete();

        $bom->auditLogs()->create([
            'company_id' => $bom->company_id,
            'user_id' => auth()->id(),
            'event' => 'archived',
            'old_values' => $oldValues,
            'new_values' => ['is_active' => false, 'deleted_at' => now()->toDateTimeString()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'BOM archived successfully.',
        ]);
    }

    /* ============================================================
     | API — Restore BOM
     ============================================================ */

    public function restore(BomHeader $bom): JsonResponse
    {
        if (!$bom->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'BOM is not archived.',
            ], 422);
        }

        $bom->restore();
        $bom->update(['is_active' => true]);

        $bom->auditLogs()->create([
            'company_id' => $bom->company_id,
            'user_id' => auth()->id(),
            'event' => 'restored',
            'old_values' => ['is_active' => false],
            'new_values' => ['is_active' => true, 'deleted_at' => null],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'BOM restored successfully.',
            'data' => $bom->load(['company', 'plant', 'product']),
        ]);
    }

    /* ============================================================
     | API — Duplicate BOM
     ============================================================ */

    public function duplicate(BomHeader $bom): JsonResponse
    {
        $newBom = DB::transaction(function () use ($bom) {
            $newCode = $bom->code . '-COPY';
            $counter = 1;
            while (BomHeader::where('company_id', $bom->company_id)->where('code', $newCode)->exists()) {
                $newCode = $bom->code . '-COPY-' . $counter++;
            }

            $clone = $bom->replicate(['created_by', 'updated_by']);
            $clone->code = $newCode;
            $clone->name = ($bom->name ?? $bom->code) . ' (Copy)';
            $clone->is_active = true;
            $clone->created_by = auth()->id();
            $clone->updated_by = auth()->id();
            $clone->save();

            foreach ($bom->versions as $version) {
                $newVersion = $version->replicate(['bom_header_id', 'approved_by', 'approved_at', 'submitted_by', 'submitted_at']);
                $newVersion->bom_header_id = $clone->id;
                $newVersion->approval_state = 'draft';
                $newVersion->is_default = false;
                $newVersion->save();

                foreach ($version->components as $component) {
                    $newComponent = $component->replicate(['bom_version_id']);
                    $newComponent->bom_version_id = $newVersion->id;
                    $newComponent->save();

                    foreach ($component->substitutes as $substitute) {
                        $newSub = $substitute->replicate(['bom_component_id']);
                        $newSub->bom_component_id = $newComponent->id;
                        $newSub->save();
                    }
                }
            }

            $clone->auditLogs()->create([
                'company_id' => $clone->company_id,
                'user_id' => auth()->id(),
                'event' => 'created',
                'new_values' => $clone->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $clone->load(['company', 'plant', 'product']);
        });

        return response()->json([
            'success' => true,
            'message' => 'BOM duplicated successfully.',
            'data' => $newBom,
        ], 201);
    }

    /* ============================================================
     | API — Where Used
     ============================================================ */

    public function whereUsed(Product $product): JsonResponse
    {
        $usedIn = BomComponent::where('product_id', $product->id)
            ->with('bomVersion.bomHeader.product')
            ->get()
            ->map(fn($comp) => [
                'bom_id' => $comp->bomVersion?->bomHeader?->id,
                'bom_code' => $comp->bomVersion?->bomHeader?->code,
                'bom_name' => $comp->bomVersion?->bomHeader?->name,
                'parent_product_code' => $comp->bomVersion?->bomHeader?->product?->code,
                'parent_product_name' => $comp->bomVersion?->bomHeader?->product?->name,
                'version' => $comp->bomVersion?->version,
                'revision' => $comp->bomVersion?->revision,
                'quantity' => $comp->quantity,
                'uom' => $comp->uom?->symbol,
            ]);

        return response()->json([
            'success' => true,
            'data' => $usedIn,
        ]);
    }

    /* ============================================================
     | API — Product Search (for component selector)
     ============================================================ */

    public function productSearch(Request $request): JsonResponse
    {
        $query = Product::where('status', 'active')
            ->with(['uom', 'productType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $this->ilike($q, 'code', "%{$search}%");
                $this->ilike($q->orWhere, 'name', "%{$search}%");
                $this->ilike($q->orWhere, 'sku', "%{$search}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('is_manufacturable')) {
            $query->where('is_manufacturable', (bool) $request->is_manufacturable);
        }

        $products = $query->limit(50)->get(['id', 'code', 'name', 'sku', 'uom_id', 'company_id']);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /* ============================================================
     | API — UOM List
     ============================================================ */

    public function uomList(): JsonResponse
    {
        $uoms = UnitOfMeasure::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'symbol', 'category']);

        return response()->json(['success' => true, 'data' => $uoms]);
    }

    /* ============================================================
     | API — Company List
     ============================================================ */

    public function companies(): JsonResponse
    {
        $companies = Company::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json(['success' => true, 'data' => $companies]);
    }

    /* ============================================================
     | API — Plant List
     ============================================================ */

    public function plants(Request $request): JsonResponse
    {
        $query = Plant::where('is_active', true);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $plants = $query->orderBy('name')
            ->get(['id', 'company_id', 'code', 'name']);

        return response()->json(['success' => true, 'data' => $plants]);
    }

    /* ============================================================
     | API — Production Process List
     ============================================================ */

    public function productionProcesses(Request $request): JsonResponse
    {
        $query = ProductionProcess::where('is_active', true);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $processes = $query->orderBy('name')
            ->get(['id', 'company_id', 'code', 'name']);

        return response()->json(['success' => true, 'data' => $processes]);
    }

    /* ============================================================
     | API — Routing Version List
     ============================================================ */

    public function routingVersions(Request $request): JsonResponse
    {
        $query = RoutingVersion::where('approval_state', 'approved')
            ->with('routingHeader:id,code,name,company_id,product_id');

        if ($request->filled('company_id')) {
            $query->whereHas('routingHeader', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        if ($request->filled('product_id')) {
            $query->whereHas('routingHeader', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        $versions = $query->orderByDesc('id')
            ->limit(50)
            ->get();

        return response()->json(['success' => true, 'data' => $versions]);
    }
}
