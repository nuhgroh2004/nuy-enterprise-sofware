<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostVariance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'production_order_id', 'product_cost_id', 'variance_type',
        'standard_amount', 'actual_amount', 'variance_amount', 'variance_percentage', 'notes',
    ];

    protected $casts = [
        'standard_amount' => 'decimal:4',
        'actual_amount' => 'decimal:4',
        'variance_amount' => 'decimal:4',
        'variance_percentage' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function productCost()
    {
        return $this->belongsTo(ProductCost::class);
    }
}
