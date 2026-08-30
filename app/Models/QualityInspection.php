<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'product_id', 'inspector_id', 'document_number', 'inspection_type',
        'source_type', 'source_id', 'source_document_number', 'production_order_id',
        'work_order_id', 'batch_number', 'serial_number', 'quantity_inspected',
        'quantity_accepted', 'quantity_rejected', 'inspection_date', 'result', 'status', 'notes',
    ];

    protected $casts = [
        'quantity_inspected' => 'decimal:4',
        'quantity_accepted' => 'decimal:4',
        'quantity_rejected' => 'decimal:4',
        'inspection_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inspector()
    {
        return $this->belongsTo(Employee::class, 'inspector_id');
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function results()
    {
        return $this->hasMany(InspectionResult::class);
    }

    public function nonConformances()
    {
        return $this->hasMany(NonConformance::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
