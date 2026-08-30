<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'plant_id', 'product_id', 'uom_id', 'source_type', 'source_id',
        'required_date', 'required_quantity', 'available_quantity', 'planned_receipt_quantity',
        'planned_release_quantity', 'shortage_quantity', 'lot_size', 'safety_stock',
        'lead_time_days', 'status', 'notes',
    ];

    protected $casts = [
        'required_date' => 'date',
        'required_quantity' => 'decimal:4',
        'available_quantity' => 'decimal:4',
        'planned_receipt_quantity' => 'decimal:4',
        'planned_release_quantity' => 'decimal:4',
        'shortage_quantity' => 'decimal:4',
        'lot_size' => 'decimal:4',
        'safety_stock' => 'decimal:4',
        'lead_time_days' => 'integer',
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

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function plannedOrders()
    {
        return $this->hasMany(PlannedOrder::class);
    }
}
