<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlannedOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'plant_id', 'material_requirement_id', 'product_id', 'uom_id',
        'order_type', 'planned_quantity', 'planned_release_date', 'planned_receipt_date',
        'lead_time_days', 'status', 'converted_order_id', 'notes',
    ];

    protected $casts = [
        'planned_release_date' => 'date',
        'planned_receipt_date' => 'date',
        'planned_quantity' => 'decimal:4',
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

    public function materialRequirement()
    {
        return $this->belongsTo(MaterialRequirement::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }
}
