<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'demand_id', 'product_id', 'uom_id', 'quantity', 'fulfilled_quantity',
        'required_date', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'fulfilled_quantity' => 'decimal:4',
        'required_date' => 'date',
    ];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
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
