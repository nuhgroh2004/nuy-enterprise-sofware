<?php

namespace App\Http\Controllers;

use App\Models\BomComponent;
use App\Models\BomSubstitute;
use App\Models\BomVersion;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomComponentController extends Controller
{
    /* ============================================================
     | API — Add Component to Version
     ============================================================ */

    public function store(Request $request, BomVersion $version): JsonResponse
    {
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'uom_id' => 'required|exists:units_of_measure,id',
            'quantity' => 'required|numeric|min:0.000001',
            'scrap_percentage' => 'nullable|numeric|min:0|max:100',
            'yield_percentage' => 'nullable|numeric|min:0|max:100',
            'is_fixed_quantity' => 'boolean',
            'is_critical' => 'boolean',
            'operation_sequence' => 'nullable|integer|min:1',
            'backflush' => 'boolean',
            'is_optional' => 'boolean',
            'alternative_group' => 'nullable|string|max:50',
            'substitute_policy' => 'nullable|in:automatic,manual,recommendation',
            'notes' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $parentProduct = $version->bomHeader->product_id;
        if ($validated['product_id'] == $parentProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Product parent cannot be the same as component.',
            ], 422);
        }

        $componentProduct = Product::find($validated['product_id']);
        if ($componentProduct && $componentProduct->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Component product must be active.',
            ], 422);
        }

        $existingComponent = BomComponent::where('bom_version_id', $version->id)
            ->where('product_id', $validated['product_id'])
            ->exists();
        if ($existingComponent) {
            return response()->json([
                'success' => false,
                'message' => 'This product is already a component in this BOM version.',
            ], 422);
        }

        if (empty($validated['sort_order'])) {
            $maxOrder = BomComponent::where('bom_version_id', $version->id)->max('sort_order');
            $validated['sort_order'] = ($maxOrder ?? 0) + 1;
        }

        $component = DB::transaction(function () use ($version, $validated) {
            $component = $version->components()->create($validated);

            $version->bomHeader->auditLogs()->create([
                'company_id' => $version->bomHeader->company_id,
                'user_id' => auth()->id(),
                'event' => 'component_added',
                'new_values' => $component->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $component->load(['product.uom', 'uom']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Component added successfully.',
            'data' => $component,
        ], 201);
    }

    /* ============================================================
     | API — Update Component
     ============================================================ */

    public function update(Request $request, BomComponent $component): JsonResponse
    {
        $version = $component->bomVersion;
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'quantity' => 'sometimes|required|numeric|min:0.000001',
            'uom_id' => 'sometimes|required|exists:units_of_measure,id',
            'scrap_percentage' => 'nullable|numeric|min:0|max:100',
            'yield_percentage' => 'nullable|numeric|min:0|max:100',
            'is_fixed_quantity' => 'boolean',
            'is_critical' => 'boolean',
            'operation_sequence' => 'nullable|integer|min:1',
            'backflush' => 'boolean',
            'is_optional' => 'boolean',
            'alternative_group' => 'nullable|string|max:50',
            'substitute_policy' => 'nullable|in:automatic,manual,recommendation',
            'notes' => 'nullable|string|max:2000',
        ]);

        $component->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Component updated successfully.',
            'data' => $component->fresh()->load(['product.uom', 'uom']),
        ]);
    }

    /* ============================================================
     | API — Remove Component
     ============================================================ */

    public function destroy(BomComponent $component): JsonResponse
    {
        $version = $component->bomVersion;
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $hasConsumption = $component->materialConsumptions()->exists();
        if ($hasConsumption) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove component with existing material consumption records.',
            ], 422);
        }

        DB::transaction(function () use ($component) {
            $component->substitutes()->delete();
            $component->delete();

            $component->bomVersion->bomHeader->auditLogs()->create([
                'company_id' => $component->bomVersion->bomHeader->company_id,
                'user_id' => auth()->id(),
                'event' => 'component_removed',
                'old_values' => $component->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Component removed successfully.',
        ]);
    }

    /* ============================================================
     | API — Reorder Components
     ============================================================ */

    public function reorder(Request $request, BomVersion $version): JsonResponse
    {
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:bom_components,id',
            'order.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($version, $validated) {
            foreach ($validated['order'] as $item) {
                BomComponent::where('id', $item['id'])
                    ->where('bom_version_id', $version->id)
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Components reordered successfully.',
        ]);
    }

    /* ============================================================
     | API — Add Substitute
     ============================================================ */

    public function addSubstitute(Request $request, BomComponent $component): JsonResponse
    {
        $version = $component->bomVersion;
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'uom_id' => 'required|exists:units_of_measure,id',
            'conversion_factor' => 'required|numeric|min:0.0001',
            'is_preferred' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($validated['product_id'] == $component->product_id) {
            return response()->json([
                'success' => false,
                'message' => 'Substitute cannot be the same as the primary component.',
            ], 422);
        }

        $substituteProduct = Product::find($validated['product_id']);
        if ($substituteProduct && $substituteProduct->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Substitute product must be active.',
            ], 422);
        }

        $existing = BomSubstitute::where('bom_component_id', $component->id)
            ->where('product_id', $validated['product_id'])
            ->exists();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This product is already a substitute for this component.',
            ], 422);
        }

        $validated['active'] = $validated['active'] ?? true;

        $substitute = $component->substitutes()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Substitute added successfully.',
            'data' => $substitute->load(['product.uom', 'uom']),
        ], 201);
    }

    /* ============================================================
     | API — Update Substitute
     ============================================================ */

    public function updateSubstitute(Request $request, BomSubstitute $substitute): JsonResponse
    {
        $version = $substitute->bomComponent->bomVersion;
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'uom_id' => 'sometimes|required|exists:units_of_measure,id',
            'conversion_factor' => 'sometimes|required|numeric|min:0.0001',
            'is_preferred' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'active' => 'boolean',
            'notes' => 'nullable|string|max:2000',
        ]);

        $substitute->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Substitute updated successfully.',
            'data' => $substitute->fresh()->load(['product.uom', 'uom']),
        ]);
    }

    /* ============================================================
     | API — Remove Substitute
     ============================================================ */

    public function removeSubstitute(BomSubstitute $substitute): JsonResponse
    {
        $version = $substitute->bomComponent->bomVersion;
        if (!$version->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft versions can be modified.',
            ], 422);
        }

        $substitute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Substitute removed successfully.',
        ]);
    }
}
