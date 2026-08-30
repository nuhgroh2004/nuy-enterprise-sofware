<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionCostTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'production_order_id', 'work_order_id', 'cost_center_id',
        'cost_type', 'amount', 'quantity', 'rate', 'description', 'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'quantity' => 'decimal:4',
        'rate' => 'decimal:4',
        'transaction_date' => 'date',
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

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
