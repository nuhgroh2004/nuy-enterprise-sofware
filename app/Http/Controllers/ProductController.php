<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BomComponent;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\ProductUom;
use App\Models\UnitOfMeasure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
     | PAGE — Product List
     ============================================================ */

    public function index()
    {
        $totalProducts = Product::count();
        $activeCount = Product::where('status', 'active')->count();
        $inactiveCount = Product::where('status', 'inactive')->count();
        $manufacturableCount = Product::where('is_manufacturable', true)->count();

        return view('MRP&Production.page.products', compact(
            'totalProducts', 'activeCount', 'inactiveCount', 'manufacturableCount'
        ));
    }

    /* ============================================================
     | PAGE — Product Detail
     ============================================================ */

    public function show(Product $product)
    {
        $product->load([
            'company', 'productType', 'productCategory', 'uom',
            'variants', 'productUoms.uom',
            'bomHeaders', 'routingHeaders',
        ]);

        return view('MRP&Production.page.product-detail', compact('product'));
    }

    /* ============================================================
     | API — List Products
     ============================================================ */

    public function api(Request $request): JsonResponse
    {
        $query = Product::with(['productType', 'productCategory', 'uom']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $this->ilike($q, 'code', "%{$search}%");
                $this->ilike($q->orWhere, 'name', "%{$search}%");
                $this->ilike($q->orWhere, 'sku', "%{$search}%");
                $this->ilike($q->orWhere, 'barcode', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        if ($request->filled('product_category_id')) {
            $query->where('product_category_id', $request->product_category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_manufacturable')) {
            $query->where('is_manufacturable', (bool) $request->is_manufacturable);
        }

        if ($request->filled('is_stockable')) {
            $query->where('is_stockable', (bool) $request->is_stockable);
        }

        if ($request->filled('is_purchasable')) {
            $query->where('is_purchasable', (bool) $request->is_purchasable);
        }

        if ($request->filled('is_sellable')) {
            $query->where('is_sellable', (bool) $request->is_sellable);
        }

        // Sort
        $sortField = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['code', 'name', 'status', 'updated_at', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('updated_at');
        }

        $products = $query->paginate($request->get('per_page', 20));

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'manufacturable' => Product::where('is_manufacturable', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $products,
            'stats' => $stats,
        ]);
    }

    /* ============================================================
     | API — Show Single Product
     ============================================================ */

    public function apiShow(Product $product): JsonResponse
    {
        $product->load([
            'company', 'productType', 'productCategory', 'uom',
            'variants', 'productUoms.uom',
            'bomHeaders.bomVersions', 'routingHeaders',
            'auditLogs.user',
        ]);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /* ============================================================
     | API — Store Product
     ============================================================ */

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'product_type_id' => 'required|exists:product_types,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'uom_id' => 'required|exists:units_of_measure,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'is_purchasable' => 'boolean',
            'is_sellable' => 'boolean',
            'is_manufacturable' => 'boolean',
            'is_stockable' => 'boolean',
            'is_batch_tracked' => 'boolean',
            'is_serial_tracked' => 'boolean',
            'is_expiry_tracked' => 'boolean',
            'standard_cost' => 'nullable|numeric|min:0',
            'average_cost' => 'nullable|numeric|min:0',
            'last_purchase_cost' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0|gte:min_stock',
            'reorder_point' => 'nullable|numeric|min:0',
            'safety_stock' => 'nullable|numeric|min:0',
            'lead_time_days' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,discontinued',
            'product_uoms' => 'nullable|array',
            'product_uoms.*.uom_id' => 'required_with:product_uoms|exists:units_of_measure,id',
            'product_uoms.*.usage_type' => 'required_with:product_uoms|in:purchasing,sales,production',
            'product_uoms.*.conversion_factor' => 'required_with:product_uoms|numeric|min:0.0001',
            'product_uoms.*.is_default' => 'boolean',
        ]);

        // Validate unique code per company
        $exists = Product::where('company_id', $validated['company_id'])
            ->where('code', $validated['code'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product code already exists in this company.',
            ], 422);
        }

        // Validate unique SKU per company (if provided)
        if (!empty($validated['sku'])) {
            $skuExists = Product::where('company_id', $validated['company_id'])
                ->where('sku', $validated['sku'])
                ->exists();
            if ($skuExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product SKU already exists in this company.',
                ], 422);
            }
        }

        // Validate unique barcode (if provided)
        if (!empty($validated['barcode'])) {
            $barcodeExists = Product::where('barcode', $validated['barcode'])->exists();
            if ($barcodeExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product barcode already exists.',
                ], 422);
            }
        }

        $productUoms = $validated['product_uoms'] ?? [];
        unset($validated['product_uoms']);
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $product = DB::transaction(function () use ($validated, $productUoms) {
            $product = Product::create($validated);

            foreach ($productUoms as $pu) {
                $product->productUoms()->create([
                    'uom_id' => $pu['uom_id'],
                    'usage_type' => $pu['usage_type'],
                    'conversion_factor' => $pu['conversion_factor'],
                    'is_default' => $pu['is_default'] ?? false,
                ]);
            }

            // Audit log
            $product->auditLogs()->create([
                'company_id' => $product->company_id,
                'user_id' => auth()->id(),
                'event' => 'created',
                'new_values' => $product->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $product->load(['productType', 'productCategory', 'uom', 'productUoms.uom']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    /* ============================================================
     | API — Update Product
     ============================================================ */

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'product_type_id' => 'sometimes|required|exists:product_types,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'uom_id' => 'sometimes|required|exists:units_of_measure,id',
            'code' => 'sometimes|required|string|max:50',
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'is_purchasable' => 'boolean',
            'is_sellable' => 'boolean',
            'is_manufacturable' => 'boolean',
            'is_stockable' => 'boolean',
            'is_batch_tracked' => 'boolean',
            'is_serial_tracked' => 'boolean',
            'is_expiry_tracked' => 'boolean',
            'standard_cost' => 'nullable|numeric|min:0',
            'average_cost' => 'nullable|numeric|min:0',
            'last_purchase_cost' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
            'safety_stock' => 'nullable|numeric|min:0',
            'lead_time_days' => 'nullable|integer|min:0',
            'status' => 'sometimes|in:active,inactive,discontinued',
            'product_uoms' => 'nullable|array',
            'product_uoms.*.id' => 'nullable|integer',
            'product_uoms.*.uom_id' => 'required_with:product_uoms|exists:units_of_measure,id',
            'product_uoms.*.usage_type' => 'required_with:product_uoms|in:purchasing,sales,production',
            'product_uoms.*.conversion_factor' => 'required_with:product_uoms|numeric|min:0.0001',
            'product_uoms.*.is_default' => 'boolean',
        ]);

        // Check if code changed and is unique
        if (isset($validated['code']) && $validated['code'] !== $product->code) {
            $exists = Product::where('company_id', $product->company_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $product->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product code already exists in this company.',
                ], 422);
            }
        }

        // Check SKU uniqueness
        if (isset($validated['sku']) && $validated['sku'] !== $product->sku) {
            $skuExists = Product::where('company_id', $product->company_id)
                ->where('sku', $validated['sku'])
                ->where('id', '!=', $product->id)
                ->exists();
            if ($skuExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product SKU already exists in this company.',
                ], 422);
            }
        }

        // Check barcode uniqueness
        if (isset($validated['barcode']) && $validated['barcode'] !== $product->barcode) {
            $barcodeExists = Product::where('barcode', $validated['barcode'])
                ->where('id', '!=', $product->id)
                ->exists();
            if ($barcodeExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product barcode already exists.',
                ], 422);
            }
        }

        $oldValues = $product->toArray();
        $productUoms = $validated['product_uoms'] ?? null;
        unset($validated['product_uoms']);
        $validated['updated_by'] = auth()->id();

        $product = DB::transaction(function () use ($product, $validated, $productUoms, $oldValues) {
            $product->update($validated);

            // Sync product UOMs if provided
            if ($productUoms !== null) {
                $existingIds = collect($productUoms)->pluck('id')->filter()->toArray();
                $product->productUoms()
                    ->whereNotIn('id', $existingIds)
                    ->delete();

                foreach ($productUoms as $pu) {
                    if (!empty($pu['id'])) {
                        ProductUom::where('id', $pu['id'])->update([
                            'uom_id' => $pu['uom_id'],
                            'usage_type' => $pu['usage_type'],
                            'conversion_factor' => $pu['conversion_factor'],
                            'is_default' => $pu['is_default'] ?? false,
                        ]);
                    } else {
                        $product->productUoms()->create([
                            'uom_id' => $pu['uom_id'],
                            'usage_type' => $pu['usage_type'],
                            'conversion_factor' => $pu['conversion_factor'],
                            'is_default' => $pu['is_default'] ?? false,
                        ]);
                    }
                }
            }

            // Audit log with diff
            $newValues = $product->toArray();
            $changed = [];
            foreach ($newValues as $key => $value) {
                if (array_key_exists($key, $oldValues) && $oldValues[$key] != $value) {
                    $changed[$key] = ['old' => $oldValues[$key], 'new' => $value];
                }
            }

            if (!empty($changed)) {
                $product->auditLogs()->create([
                    'company_id' => $product->company_id,
                    'user_id' => auth()->id(),
                    'event' => 'updated',
                    'old_values' => $changed,
                    'new_values' => $newValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            return $product->load(['productType', 'productCategory', 'uom', 'productUoms.uom']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product,
        ]);
    }

    /* ============================================================
     | API — Archive Product (Soft Delete)
     ============================================================ */

    public function archive(Product $product): JsonResponse
    {
        if ($product->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already archived.',
            ], 422);
        }

        // Check if product has active BOMs
        $hasActiveBom = $product->bomHeaders()
            ->whereHas('bomVersions', function ($q) {
                $q->where('approval_state', 'approved');
            })->exists();

        if ($hasActiveBom) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive product with active BOM versions. Please deactivate BOMs first.',
            ], 422);
        }

        // Check if product has open production orders
        $hasOpenProductionOrder = $product->productionOrders()
            ->whereIn('status', ['draft', 'planned', 'released', 'in_progress'])
            ->exists();

        if ($hasOpenProductionOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive product with open production orders. Please complete or cancel them first.',
            ], 422);
        }

        $oldValues = $product->toArray();

        $product->update(['status' => 'inactive']);
        $product->delete();

        // Audit log
        $product->auditLogs()->create([
            'company_id' => $product->company_id,
            'user_id' => auth()->id(),
            'event' => 'archived',
            'old_values' => $oldValues,
            'new_values' => ['status' => 'inactive', 'deleted_at' => now()->toDateTimeString()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product archived successfully.',
        ]);
    }

    /* ============================================================
     | API — Restore Product
     ============================================================ */

    public function restore(Product $product): JsonResponse
    {
        if (!$product->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not archived.',
            ], 422);
        }

        $product->restore();
        $product->update(['status' => 'active']);

        // Audit log
        $product->auditLogs()->create([
            'company_id' => $product->company_id,
            'user_id' => auth()->id(),
            'event' => 'restored',
            'old_values' => ['status' => 'inactive', 'deleted_at' => $product->deleted_at],
            'new_values' => ['status' => 'active', 'deleted_at' => null],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully.',
            'data' => $product->load(['productType', 'productCategory', 'uom']),
        ]);
    }

    /* ============================================================
     | API — Duplicate Product
     ============================================================ */

    public function duplicate(Product $product): JsonResponse
    {
        $newProduct = DB::transaction(function () use ($product) {
            $newCode = $product->code . '-COPY';
            $counter = 1;
            while (Product::where('company_id', $product->company_id)->where('code', $newCode)->exists()) {
                $newCode = $product->code . '-COPY-' . $counter++;
            }

            $clone = $product->replicate([
                'sku', 'barcode', 'created_by', 'updated_by',
            ]);
            $clone->code = $newCode;
            $clone->name = $product->name . ' (Copy)';
            $clone->status = 'active';
            $clone->created_by = auth()->id();
            $clone->updated_by = auth()->id();
            $clone->save();

            // Duplicate product UOMs
            foreach ($product->productUoms as $pu) {
                $clone->productUoms()->create([
                    'uom_id' => $pu->uom_id,
                    'usage_type' => $pu->usage_type,
                    'conversion_factor' => $pu->conversion_factor,
                    'is_default' => $pu->is_default,
                ]);
            }

            // Audit log
            $clone->auditLogs()->create([
                'company_id' => $clone->company_id,
                'user_id' => auth()->id(),
                'event' => 'created',
                'new_values' => $clone->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $clone->load(['productType', 'productCategory', 'uom']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product duplicated successfully.',
            'data' => $newProduct,
        ], 201);
    }

    /* ============================================================
     | API — Variants
     ============================================================ */

    public function variants(Product $product): JsonResponse
    {
        $variants = $product->variants()->latest()->paginate(
            $request->get('per_page', 20)
        );

        return response()->json([
            'success' => true,
            'data' => $variants,
        ]);
    }

    public function storeVariant(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'attributes' => 'nullable|array',
            'additional_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Validate unique code within product
        $exists = ProductVariant::where('product_id', $product->id)
            ->where('code', $validated['code'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Variant code already exists for this product.',
            ], 422);
        }

        // Validate unique SKU if provided
        if (!empty($validated['sku'])) {
            $skuExists = ProductVariant::where('sku', $validated['sku'])->exists();
            if ($skuExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant SKU already exists.',
                ], 422);
            }
        }

        $variant = $product->variants()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Variant created successfully.',
            'data' => $variant,
        ], 201);
    }

    public function updateVariant(Request $request, ProductVariant $variant): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'attributes' => 'nullable|array',
            'additional_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['sku']) && $validated['sku'] !== $variant->sku) {
            $skuExists = ProductVariant::where('sku', $validated['sku'])
                ->where('id', '!=', $variant->id)
                ->exists();
            if ($skuExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant SKU already exists.',
                ], 422);
            }
        }

        $variant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully.',
            'data' => $variant,
        ]);
    }

    public function destroyVariant(ProductVariant $variant): JsonResponse
    {
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully.',
        ]);
    }

    /* ============================================================
     | API — Usage Summary
     ============================================================ */

    public function usageSummary(Product $product): JsonResponse
    {
        $bomCount = $product->bomHeaders()->count();
        $routingCount = $product->routingHeaders()->count();
        $productionOrderCount = $product->productionOrders()->count();
        $stockBalanceTotal = (float) $product->stockBalances()->sum('quantity');

        $usedInBoms = BomComponent::where('product_id', $product->id)
            ->with('bomVersion.bomHeader')
            ->get()
            ->map(fn($comp) => [
                'bom_code' => $comp->bomVersion?->bomHeader?->code,
                'bom_name' => $comp->bomVersion?->bomHeader?->name,
                'quantity' => $comp->quantity,
                'uom_id' => $comp->uom_id,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'bom_count' => $bomCount,
                'routing_count' => $routingCount,
                'production_order_count' => $productionOrderCount,
                'stock_balance_total' => $stockBalanceTotal,
                'used_in_boms' => $usedInBoms,
            ],
        ]);
    }

    /* ============================================================
     | API — Reference Data
     ============================================================ */

    public function productTypes(): JsonResponse
    {
        $types = ProductType::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json(['success' => true, 'data' => $types]);
    }

    public function productCategories(): JsonResponse
    {
        $categories = ProductCategory::where('is_active', true)
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function uoms(): JsonResponse
    {
        $uoms = UnitOfMeasure::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'symbol', 'category']);

        return response()->json(['success' => true, 'data' => $uoms]);
    }
}
