<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkCenter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'plant_id', 'code', 'name', 'production_process_id',
        'capacity_per_hour', 'uom_id', 'setup_cost_per_hour', 'run_cost_per_hour',
        'labor_cost_per_hour', 'is_active',
    ];

    protected $casts = [
        'capacity_per_hour' => 'decimal:4',
        'setup_cost_per_hour' => 'decimal:4',
        'run_cost_per_hour' => 'decimal:4',
        'labor_cost_per_hour' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function plant() { return $this->belongsTo(Plant::class); }
    public function productionProcess() { return $this->belongsTo(ProductionProcess::class); }
    public function uom() { return $this->belongsTo(UnitOfMeasure::class, 'uom_id'); }
    public function machines() { return $this->hasMany(Machine::class); }
    public function routingOperations() { return $this->hasMany(RoutingOperation::class); }
    public function workOrders() { return $this->hasMany(WorkOrder::class); }
}
