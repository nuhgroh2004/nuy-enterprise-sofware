<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'quality_inspection_id', 'production_order_id', 'work_order_id',
        'product_id', 'reason_code_id', 'document_number', 'severity', 'description',
        'disposition', 'affected_quantity', 'estimated_cost', 'status', 'root_cause',
        'corrective_action', 'target_date', 'closed_date',
    ];

    protected $casts = [
        'affected_quantity' => 'decimal:4',
        'estimated_cost' => 'decimal:4',
        'target_date' => 'date',
        'closed_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function qualityInspection()
    {
        return $this->belongsTo(QualityInspection::class);
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

    public function reworkOrders()
    {
        return $this->hasMany(ReworkOrder::class);
    }
}
