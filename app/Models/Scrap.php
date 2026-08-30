<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrap extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'production_order_id', 'work_order_id', 'product_id', 'reason_code_id',
        'uom_id', 'warehouse_id', 'quantity', 'estimated_cost', 'scrap_date', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'estimated_cost' => 'decimal:4',
        'scrap_date' => 'date',
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

    public function reasonCode()
    {
        return $this->belongsTo(ReasonCode::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
