<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutingOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'routing_version_id',
        'sequence',
        'code',
        'name',
        'work_center_id',
        'machine_id',
        'production_process_id',
        'setup_time_minutes',
        'run_time_minutes',
        'queue_time_minutes',
        'wait_time_minutes',
        'labor_required',
        'machine_required',
        'standard_output',
        'output_uom_id',
        'scrap_percentage',
        'quality_checkpoint',
        'notes',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'setup_time_minutes' => 'decimal:2',
        'run_time_minutes' => 'decimal:2',
        'queue_time_minutes' => 'decimal:2',
        'wait_time_minutes' => 'decimal:2',
        'labor_required' => 'integer',
        'machine_required' => 'integer',
        'standard_output' => 'decimal:4',
        'scrap_percentage' => 'decimal:2',
    ];

    public function routingVersion(): BelongsTo
    {
        return $this->belongsTo(RoutingVersion::class);
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function productionProcess(): BelongsTo
    {
        return $this->belongsTo(ProductionProcess::class);
    }

    public function outputUom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'output_uom_id');
    }

    public function dependencies(): HasMany
    {
        return $this->hasMany(RoutingOperationDependency::class, 'routing_operation_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(RoutingOperationDependency::class, 'depends_on_operation_id');
    }

    public function workOrderOperations(): HasMany
    {
        return $this->hasMany(WorkOrderOperation::class);
    }
}
