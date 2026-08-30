<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'production_order_id', 'work_center_id', 'machine_id', 'operator_id',
        'routing_operation_id', 'document_number', 'sequence', 'planned_quantity',
        'actual_quantity', 'rejected_quantity', 'scrap_quantity', 'setup_time_minutes',
        'run_time_minutes', 'downtime_minutes', 'started_at', 'completed_at', 'status', 'notes',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'planned_quantity' => 'decimal:4',
        'actual_quantity' => 'decimal:4',
        'rejected_quantity' => 'decimal:4',
        'scrap_quantity' => 'decimal:4',
        'setup_time_minutes' => 'decimal:2',
        'run_time_minutes' => 'decimal:2',
        'downtime_minutes' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function workCenter()
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function operator()
    {
        return $this->belongsTo(Employee::class, 'operator_id');
    }

    public function routingOperation()
    {
        return $this->belongsTo(RoutingOperation::class);
    }

    public function materialConsumptions()
    {
        return $this->hasMany(MaterialConsumption::class);
    }

    public function productionResults()
    {
        return $this->hasMany(ProductionResult::class);
    }

    public function downtimeRecords()
    {
        return $this->hasMany(DowntimeRecord::class);
    }
}
