<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\DemandLine;
use App\Models\MasterProductionSchedule;
use App\Models\MpsLine;
use App\Models\MaterialRequirement;
use App\Models\PlannedOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningController extends Controller
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
     | DEMAND PLANNING — PAGE
     ============================================================ */

    public function demandPlanning()
    {
        $demands = Demand::with(['lines.product', 'plant'])
            ->latest('demand_date')
            ->paginate(15);

        $totalDemand = DemandLine::sum('quantity');
        $totalFulfilled = DemandLine::sum('fulfilled_quantity');
        $demandCount = Demand::count();
        $draftCount = Demand::where('status', 'draft')->count();

        return view('MRP&Production.page.demand-planning', compact(
            'demands', 'totalDemand', 'totalFulfilled', 'demandCount', 'draftCount'
        ));
    }

    /* ============================================================
     | DEMAND PLANNING — API
     ============================================================ */

    public function demandApi(Request $request): JsonResponse
    {
        $query = Demand::with(['lines.product', 'lines.uom', 'plant']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lines.product', function ($q) use ($search) {
                $this->ilike($q, 'name', "%{$search}%");
                $this->ilike($q->orWhere, 'code', "%{$search}%");
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $demands = $query->latest('demand_date')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $demands,
            'stats' => [
                'total_demand' => (float) DemandLine::sum('quantity'),
                'total_fulfilled' => (float) DemandLine::sum('fulfilled_quantity'),
                'demand_count' => Demand::count(),
                'draft_count' => Demand::where('status', 'draft')->count(),
            ],
        ]);
    }

    public function demandStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plant_id' => 'required|exists:plants,id',
            'source_type' => 'required|in:manual,sales_order,forecast,other',
            'demand_date' => 'required|date',
            'required_date' => 'required|date|after_or_equal:demand_date',
            'priority' => 'required|in:low,normal,high,urgent',
            'notes' => 'nullable|string|max:500',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'required|exists:products,id',
            'lines.*.uom_id' => 'required|exists:units_of_measure,id',
            'lines.*.quantity' => 'required|numeric|min:0.0001',
            'lines.*.required_date' => 'nullable|date',
        ]);

        $documentNumber = $this->generateDemandNumber($validated['company_id']);
        $lines = $validated['lines'];
        unset($validated['lines']);

        $demand = DB::transaction(function () use ($validated, $documentNumber, $lines) {
            $demand = Demand::create([
                ...$validated,
                'document_number' => $documentNumber,
                'status' => 'draft',
            ]);

            foreach ($lines as $line) {
                $demand->lines()->create([
                    'product_id' => $line['product_id'],
                    'uom_id' => $line['uom_id'],
                    'quantity' => $line['quantity'],
                    'required_date' => $line['required_date'] ?? $validated['required_date'],
                ]);
            }

            return $demand->load('lines.product');
        });

        return response()->json([
            'success' => true,
            'message' => 'Demand created successfully.',
            'data' => $demand,
        ], 201);
    }

    public function demandShow(Demand $demand): JsonResponse
    {
        $demand->load(['lines.product', 'lines.uom', 'plant', 'company']);

        return response()->json([
            'success' => true,
            'data' => $demand,
        ]);
    }

    public function demandUpdate(Request $request, Demand $demand): JsonResponse
    {
        if ($demand->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft demands can be updated.',
            ], 422);
        }

        $validated = $request->validate([
            'priority' => 'sometimes|in:low,normal,high,urgent',
            'required_date' => 'sometimes|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $demand->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Demand updated successfully.',
            'data' => $demand,
        ]);
    }

    public function demandDestroy(Demand $demand): JsonResponse
    {
        if (in_array($demand->status, ['confirmed', 'planned', 'fulfilled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a submitted or processed demand.',
            ], 422);
        }

        DB::transaction(function () use ($demand) {
            $demand->lines()->delete();
            $demand->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Demand deleted successfully.',
        ]);
    }

    public function demandSubmit(Demand $demand): JsonResponse
    {
        if ($demand->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft demands can be submitted.',
            ], 422);
        }

        $demand->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Demand submitted successfully.',
            'data' => $demand,
        ]);
    }

    public function demandCancel(Demand $demand): JsonResponse
    {
        if (in_array($demand->status, ['fulfilled', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this demand.',
            ], 422);
        }

        $demand->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Demand cancelled successfully.',
            'data' => $demand,
        ]);
    }

    /* ============================================================
     | MASTER PRODUCTION SCHEDULE — PAGE
     ============================================================ */

    public function masterProductionSchedule()
    {
        $schedules = MasterProductionSchedule::with(['lines.product', 'plant'])
            ->latest('plan_date')
            ->paginate(15);

        $totalPlanned = MpsLine::sum('planned_quantity');
        $scheduleCount = MasterProductionSchedule::count();
        $activeCount = MasterProductionSchedule::where('status', 'confirmed')->count();

        return view('MRP&Production.page.master-production-schedule', compact(
            'schedules', 'totalPlanned', 'scheduleCount', 'activeCount'
        ));
    }

    /* ============================================================
     | MASTER PRODUCTION SCHEDULE — API
     ============================================================ */

    public function mpsApi(Request $request): JsonResponse
    {
        $query = MasterProductionSchedule::with(['lines.product', 'lines.demand', 'plant']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lines.product', function ($q) use ($search) {
                $this->ilike($q, 'name', "%{$search}%");
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('plan_date', [$request->from_date, $request->to_date]);
        }

        $schedules = $query->latest('plan_date')
            ->paginate($request->get('per_page', 15));

        $stats = [
            'total_planned' => (float) MpsLine::sum('planned_quantity'),
            'schedule_count' => MasterProductionSchedule::count(),
            'active_count' => MasterProductionSchedule::where('status', 'confirmed')->count(),
            'delayed_count' => MpsLine::where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $schedules,
            'stats' => $stats,
        ]);
    }

    public function mpsShow(MasterProductionSchedule $schedule): JsonResponse
    {
        $schedule->load(['lines.product', 'lines.demand', 'lines.uom', 'plant']);

        return response()->json([
            'success' => true,
            'data' => $schedule,
        ]);
    }

    public function mpsStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plant_id' => 'required|exists:plants,id',
            'plan_date' => 'required|date',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after:from_date',
            'notes' => 'nullable|string|max:500',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'required|exists:products,id',
            'lines.*.uom_id' => 'required|exists:units_of_measure,id',
            'lines.*.demand_id' => 'nullable|exists:demands,id',
            'lines.*.planned_date' => 'required|date',
            'lines.*.demand_quantity' => 'required|numeric|min:0',
            'lines.*.planned_quantity' => 'required|numeric|min:0.0001',
        ]);

        $documentNumber = $this->generateMpsNumber($validated['company_id']);
        $lines = $validated['lines'];
        unset($validated['lines']);

        $schedule = DB::transaction(function () use ($validated, $documentNumber, $lines) {
            $schedule = MasterProductionSchedule::create([
                ...$validated,
                'document_number' => $documentNumber,
                'status' => 'draft',
            ]);

            foreach ($lines as $line) {
                $available = $line['demand_quantity'] - $line['planned_quantity'];
                $schedule->lines()->create([
                    'product_id' => $line['product_id'],
                    'uom_id' => $line['uom_id'],
                    'demand_id' => $line['demand_id'] ?? null,
                    'planned_date' => $line['planned_date'],
                    'demand_quantity' => $line['demand_quantity'],
                    'planned_quantity' => $line['planned_quantity'],
                    'available_quantity' => max(0, $available),
                    'projected_balance' => $available,
                    'status' => 'planned',
                ]);
            }

            return $schedule->load('lines.product');
        });

        return response()->json([
            'success' => true,
            'message' => 'MPS created successfully.',
            'data' => $schedule,
        ], 201);
    }

    public function mpsUpdate(Request $request, MasterProductionSchedule $schedule): JsonResponse
    {
        if ($schedule->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft schedules can be updated.',
            ], 422);
        }

        $validated = $request->validate([
            'plan_date' => 'sometimes|date',
            'from_date' => 'sometimes|date',
            'to_date' => 'sometimes|date|after:from_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'MPS updated successfully.',
            'data' => $schedule,
        ]);
    }

    public function mpsDestroy(MasterProductionSchedule $schedule): JsonResponse
    {
        if (in_array($schedule->status, ['confirmed', 'frozen'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a confirmed or frozen schedule.',
            ], 422);
        }

        DB::transaction(function () use ($schedule) {
            $schedule->lines()->delete();
            $schedule->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'MPS deleted successfully.',
        ]);
    }

    public function mpsSubmit(MasterProductionSchedule $schedule): JsonResponse
    {
        if ($schedule->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft schedules can be submitted.',
            ], 422);
        }

        $schedule->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'MPS confirmed successfully.',
            'data' => $schedule,
        ]);
    }

    /* ============================================================
     | MATERIAL REQUIREMENTS — PAGE
     ============================================================ */

    public function materialRequirements()
    {
        $requirements = MaterialRequirement::with(['product', 'uom'])
            ->latest('required_date')
            ->paginate(15);

        $totalRequired = MaterialRequirement::sum('required_quantity');
        $totalAvailable = MaterialRequirement::sum('available_quantity');
        $totalShortage = MaterialRequirement::sum('shortage_quantity');
        $shortageCount = MaterialRequirement::where('shortage_quantity', '>', 0)->count();

        return view('MRP&Production.page.material-requirements', compact(
            'requirements', 'totalRequired', 'totalAvailable', 'totalShortage', 'shortageCount'
        ));
    }

    /* ============================================================
     | MATERIAL REQUIREMENTS — API
     ============================================================ */

    public function mrpApi(Request $request): JsonResponse
    {
        $query = MaterialRequirement::with(['product', 'uom', 'plannedOrders']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $this->ilike($q, 'name', "%{$search}%");
                $this->ilike($q->orWhere, 'code', "%{$search}%");
            });
        }

        if ($request->filled('has_shortage')) {
            $query->where('shortage_quantity', '>', 0);
        }

        $requirements = $query->latest('required_date')
            ->paginate($request->get('per_page', 15));

        $stats = [
            'total_required' => (float) MaterialRequirement::sum('required_quantity'),
            'total_available' => (float) MaterialRequirement::sum('available_quantity'),
            'total_shortage' => (float) MaterialRequirement::sum('shortage_quantity'),
            'shortage_count' => MaterialRequirement::where('shortage_quantity', '>', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $requirements,
            'stats' => $stats,
        ]);
    }

    public function mrpCalculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plant_id' => 'required|exists:plants,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $plantId = $validated['plant_id'];
        $productIds = $validated['product_ids'] ?? [];

        $demands = Demand::where('company_id', $validated['company_id'])
            ->where('plant_id', $plantId)
            ->whereIn('status', ['confirmed'])
            ->with('lines.product')
            ->get();

        $calculated = 0;

        DB::transaction(function () use ($demands, $plantId, $productIds, &$calculated) {
            foreach ($demands as $demand) {
                foreach ($demand->lines as $line) {
                    if (!empty($productIds) && !in_array($line->product_id, $productIds)) {
                        continue;
                    }

                    $product = $line->product;
                    $requiredQty = (float) $line->quantity;
                    $availableQty = $this->getAvailableStock($line->product_id, $plantId);
                    $safetyStock = (float) ($product->safety_stock ?? 0);
                    $leadTime = (int) ($product->lead_time_days ?? 7);
                    $shortage = max(0, $requiredQty - $availableQty + $safetyStock);

                    MaterialRequirement::updateOrCreate(
                        [
                            'company_id' => $demand->company_id,
                            'plant_id' => $plantId,
                            'product_id' => $line->product_id,
                        ],
                        [
                            'uom_id' => $line->uom_id,
                            'source_type' => 'demand',
                            'source_id' => $demand->id,
                            'required_date' => $line->required_date,
                            'required_quantity' => $requiredQty,
                            'available_quantity' => $availableQty,
                            'safety_stock' => $safetyStock,
                            'lead_time_days' => $leadTime,
                            'shortage_quantity' => $shortage,
                            'planned_receipt_quantity' => $shortage > 0 ? $shortage : 0,
                            'planned_release_quantity' => $shortage > 0 ? $shortage : 0,
                            'lot_size' => $requiredQty,
                            'status' => $shortage > 0 ? 'planned' : 'draft',
                        ]
                    );
                    $calculated++;
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => "MRP calculation completed. {$calculated} materials processed.",
            'calculated' => $calculated,
        ]);
    }

    /* ============================================================
     | PLANNED ORDERS — API
     ============================================================ */

    public function plannedOrderApi(Request $request): JsonResponse
    {
        $query = PlannedOrder::with(['product', 'uom', 'materialRequirement']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->latest('planned_release_date')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function plannedOrderFirm(Request $request, PlannedOrder $order): JsonResponse
    {
        if ($order->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft planned orders can be firmed.',
            ], 422);
        }

        $order->update(['status' => 'firm']);

        return response()->json([
            'success' => true,
            'message' => 'Planned order firmed.',
            'data' => $order,
        ]);
    }

    /* ============================================================
     | DASHBOARD STATS
     ============================================================ */

    public function planningDashboardStats(): JsonResponse
    {
        $stats = [
            'total_demand' => (float) DemandLine::sum('quantity'),
            'total_planned_production' => (float) MpsLine::sum('planned_quantity'),
            'total_material_required' => (float) MaterialRequirement::sum('required_quantity'),
            'total_shortage' => (float) MaterialRequirement::sum('shortage_quantity'),
            'active_schedules' => MasterProductionSchedule::where('status', 'confirmed')->count(),
            'draft_demands' => Demand::where('status', 'draft')->count(),
            'shortage_items' => MaterialRequirement::where('shortage_quantity', '>', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /* ============================================================
     | HELPERS
     ============================================================ */

    private function generateDemandNumber(int $companyId): string
    {
        $prefix = 'DM';
        $year = date('Y');
        $month = date('m');
        $last = Demand::where('company_id', $companyId)
            ->where('document_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->latest('id')
            ->value('document_number');

        $sequence = 1;
        if ($last) {
            $parts = explode('-', $last);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $sequence);
    }

    private function generateMpsNumber(int $companyId): string
    {
        $prefix = 'MPS';
        $year = date('Y');
        $month = date('m');
        $last = MasterProductionSchedule::where('company_id', $companyId)
            ->where('document_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->latest('id')
            ->value('document_number');

        $sequence = 1;
        if ($last) {
            $parts = explode('-', $last);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $sequence);
    }

    private function getAvailableStock(int $productId, int $plantId): float
    {
        $stockBalance = DB::table('stock_balances')
            ->where('product_id', $productId)
            ->where('plant_id', $plantId)
            ->sum('quantity');

        return (float) max(0, $stockBalance);
    }
}
