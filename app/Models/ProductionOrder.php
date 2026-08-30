<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'plant_id', 'product_id', 'bom_version_id', 'routing_version_id',
        'warehouse_id', 'planned_order_id', 'demand_id', 'uom_id', 'document_number',
        'planned_quantity', 'confirmed_quantity', 'produced_quantity', 'rejected_quantity',
        'scrap_quantity', 'planned_start_date', 'planned_finish_date', 'actual_start_date',
        'actual_finish_date', 'priority', 'status', 'notes',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:4',
        'confirmed_quantity' => 'decimal:4',
        'produced_quantity' => 'decimal:4',
        'rejected_quantity' => 'decimal:4',
        'scrap_quantity' => 'decimal:4',
        'planned_start_date' => 'date',
        'planned_finish_date' => 'date',
        'actual_start_date' => 'datetime',
        'actual_finish_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bomVersion()
    {
        return $this->belongsTo(BomVersion::class);
    }

    public function routingVersion()
    {
        return $this->belongsTo(RoutingVersion::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function plannedOrder()
    {
        return $this->belongsTo(PlannedOrder::class);
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function materialConsumptions()
    {
        return $this->hasMany(MaterialConsumption::class);
    }

    public function productionResults()
    {
        return $this->hasMany(ProductionResult::class);
    }

    public function scraps()
    {
        return $this->hasMany(Scrap::class);
    }
}
