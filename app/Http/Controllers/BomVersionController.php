<?php

namespace App\Http\Controllers;

use App\Models\BomHeader;
use App\Models\BomVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomVersionController extends Controller
{
    /* ============================================================
     | API — Create New Version
     ============================================================ */

    public function store(Request $request, BomHeader $bom): JsonResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'revision' => 'nullable|string|max:20',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'output_qty' => 'nullable|numeric|min:0',
            'output_uom_id' => 'nullable|exists:units_of_measure,id',
            'yield_percent' => 'nullable|numeric|min:0|max:100',
            'routing_version_id' => 'nullable|exists:routing_versions,id',
            'notes' => 'nullable|string|max:2000',
        ]);

        $exists = BomVersion::where('bom_header_id', $bom->id)
            ->where('version', $validated['version'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Version already exists for this BOM.',
            ], 422);
        }

        $version = DB::transaction(function () use ($bom, $validated) {
            BomVersion::where('bom_header_id', $bom->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);

            $version = $bom->versions()->create(array_merge($validated, [
                'approval_state' => 'draft',
                'is_default' => true,
            ]));

            $bom->auditLogs()->create([
                'company_id' => $bom->company_id,
                'user_id' => auth()->id(),
                'event' => 'version_created',
                'new_values' => $version->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $version;
        });

        return response()->json([
            'success' => true,
            'message' => 'Version created successfully.',
            'data' => $version,
        ], 201);
    }

    /* ============================================================
     | API — Update Draft Version
     ============================================================ */

    public function update(Request $request, BomVersion $version): JsonResponse
    {
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be edited.',
            ], 422);
        }

        $validated = $request->validate([
            'version' => 'sometimes|required|string|max:50',
            'revision' => 'nullable|string|max:20',
            'effective_date' => 'sometimes|required|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'output_qty' => 'nullable|numeric|min:0',
            'output_uom_id' => 'nullable|exists:units_of_measure,id',
            'yield_percent' => 'nullable|numeric|min:0|max:100',
            'routing_version_id' => 'nullable|exists:routing_versions,id',
            'notes' => 'nullable|string|max:2000',
        ]);

        if (isset($validated['version']) && $validated['version'] !== $version->version) {
            $exists = BomVersion::where('bom_header_id', $version->bom_header_id)
                ->where('version', $validated['version'])
                ->where('id', '!=', $version->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Version already exists for this BOM.',
                ], 422);
            }
        }

        $version->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Version updated successfully.',
            'data' => $version->fresh(),
        ]);
    }

    /* ============================================================
     | API — Submit Version for Approval
     ============================================================ */

    public function submit(BomVersion $version): JsonResponse
    {
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be submitted.',
            ], 422);
        }

        if ($version->components()->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot submit BOM with no components.',
            ], 422);
        }

        $version->update([
            'approval_state' => 'pending',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        $version->bomHeader->auditLogs()->create([
            'company_id' => $version->bomHeader->company_id,
            'user_id' => auth()->id(),
            'event' => 'submitted',
            'new_values' => ['version_id' => $version->id, 'version' => $version->version],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Version submitted for approval.',
            'data' => $version->fresh(),
        ]);
    }

    /* ============================================================
     | API — Approve Version
     ============================================================ */

    public function approve(BomVersion $version): JsonResponse
    {
        if (!$version->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending versions can be approved.',
            ], 422);
        }

        DB::transaction(function () use ($version) {
            BomVersion::where('bom_header_id', $version->bom_header_id)
                ->where('id', '!=', $version->id)
                ->update(['is_default' => false]);

            $version->update([
                'approval_state' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'is_default' => true,
            ]);

            $version->bomHeader->auditLogs()->create([
                'company_id' => $version->bomHeader->company_id,
                'user_id' => auth()->id(),
                'event' => 'approved',
                'new_values' => ['version_id' => $version->id, 'version' => $version->version],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Version approved successfully.',
            'data' => $version->fresh(),
        ]);
    }

    /* ============================================================
     | API — Expire Version
     ============================================================ */

    public function expire(BomVersion $version): JsonResponse
    {
        if (!$version->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Only approved versions can be expired.',
            ], 422);
        }

        $version->update([
            'expiry_date' => now()->toDateString(),
            'is_default' => false,
        ]);

        $version->bomHeader->auditLogs()->create([
            'company_id' => $version->bomHeader->company_id,
            'user_id' => auth()->id(),
            'event' => 'expired',
            'new_values' => ['version_id' => $version->id, 'version' => $version->version],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Version expired successfully.',
            'data' => $version->fresh(),
        ]);
    }

    /* ============================================================
     | API — Set Primary Version
     ============================================================ */

    public function setPrimary(BomVersion $version): JsonResponse
    {
        DB::transaction(function () use ($version) {
            BomVersion::where('bom_header_id', $version->bom_header_id)
                ->update(['is_default' => false]);

            $version->update(['is_default' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Version set as primary.',
            'data' => $version->fresh(),
        ]);
    }

    /* ============================================================
     | API — Compare Two Versions
     ============================================================ */

    public function compare(Request $request, BomHeader $bom): JsonResponse
    {
        $validated = $request->validate([
            'version_a' => 'required|exists:bom_versions,id',
            'version_b' => 'required|exists:bom_versions,id',
        ]);

        $versionA = BomVersion::with(['components.product', 'components.uom'])->find($validated['version_a']);
        $versionB = BomVersion::with(['components.product', 'components.uom'])->find($validated['version_b']);

        if ($versionA->bom_header_id !== $bom->id || $versionB->bom_header_id !== $bom->id) {
            return response()->json([
                'success' => false,
                'message' => 'Versions do not belong to this BOM.',
            ], 422);
        }

        $componentsA = $versionA->components->keyBy('product_id');
        $componentsB = $versionB->components->keyBy('product_id');

        $allProductIds = $componentsA->keys()->merge($componentsB->keys())->unique();

        $diff = $allProductIds->map(function ($productId) use ($componentsA, $componentsB) {
            $a = $componentsA->get($productId);
            $b = $componentsB->get($productId);

            $status = 'unchanged';
            if ($a && !$b) {
                $status = 'removed';
            } elseif (!$a && $b) {
                $status = 'added';
            } elseif ($a && $b) {
                if ($a->quantity != $b->quantity || $a->uom_id != $b->uom_id) {
                    $status = 'modified';
                }
            }

            return [
                'product_id' => $productId,
                'product_code' => $a?->product?->code ?? $b?->product?->code,
                'product_name' => $a?->product?->name ?? $b?->product?->name,
                'status' => $status,
                'version_a' => $a ? [
                    'quantity' => $a->quantity,
                    'uom' => $a->uom?->symbol,
                    'scrap_percentage' => $a->scrap_percentage,
                ] : null,
                'version_b' => $b ? [
                    'quantity' => $b->quantity,
                    'uom' => $b->uom?->symbol,
                    'scrap_percentage' => $b->scrap_percentage,
                ] : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'version_a' => $versionA,
                'version_b' => $versionB,
                'diff' => $diff,
            ],
        ]);
    }

    /* ============================================================
     | API — Version History
     ============================================================ */

    public function history(BomHeader $bom): JsonResponse
    {
        $versions = $bom->versions()
            ->with(['components', 'submittedBy', 'approvedByUser'])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $versions,
        ]);
    }
}
