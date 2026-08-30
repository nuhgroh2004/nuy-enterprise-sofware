<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'product_id', 'bom_version_id', 'version', 'effective_date',
        'expiry_date', 'material_cost', 'labor_cost', 'machine_cost', 'overhead_cost',
        'total_cost', 'unit_cost', 'cost_type', 'is_current', 'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'material_cost' => 'decimal:4',
        'labor_cost' => 'decimal:4',
        'machine_cost' => 'decimal:4',
        'overhead_cost' => 'decimal:4',
        'total_cost' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'is_current' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bomVersion()
    {
        return $this->belongsTo(BomVersion::class);
    }

    public function costVariances()
    {
        return $this->hasMany(CostVariance::class);
    }
}
