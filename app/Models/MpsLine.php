<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpsLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_production_schedule_id', 'product_id', 'demand_id', 'uom_id',
        'planned_date', 'demand_quantity', 'planned_quantity', 'available_quantity',
        'projected_balance', 'status', 'notes',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'demand_quantity' => 'decimal:4',
        'planned_quantity' => 'decimal:4',
        'available_quantity' => 'decimal:4',
        'projected_balance' => 'decimal:4',
    ];

    protected $table = 'mps_lines';

    public function masterProductionSchedule()
    {
        return $this->belongsTo(MasterProductionSchedule::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }
}
