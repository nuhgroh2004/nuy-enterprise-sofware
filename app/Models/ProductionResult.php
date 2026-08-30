<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'production_order_id', 'work_order_id', 'product_id', 'uom_id',
        'warehouse_id', 'location_id', 'document_number', 'good_quantity', 'rejected_quantity',
        'scrap_quantity', 'batch_number', 'result_date', 'status', 'notes',
    ];

    protected $casts = [
        'good_quantity' => 'decimal:4',
        'rejected_quantity' => 'decimal:4',
        'scrap_quantity' => 'decimal:4',
        'result_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location()
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function qualityInspections()
    {
        return $this->hasMany(QualityInspection::class);
    }
}
