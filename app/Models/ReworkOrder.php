<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReworkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'non_conformance_id', 'production_order_id', 'work_order_id',
        'product_id', 'work_center_id', 'uom_id', 'document_number', 'quantity',
        'reworked_quantity', 'scrapped_quantity', 'description', 'status',
        'started_at', 'completed_at', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'reworked_quantity' => 'decimal:4',
        'scrapped_quantity' => 'decimal:4',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function nonConformance()
    {
        return $this->belongsTo(NonConformance::class);
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

    public function workCenter()
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }
}
