<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutingOperationDependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'routing_operation_id',
        'depends_on_operation_id',
        'dependency_type',
        'lag_time_minutes',
    ];

    protected $casts = [
        'lag_time_minutes' => 'decimal:2',
    ];

    public function routingOperation(): BelongsTo
    {
        return $this->belongsTo(RoutingOperation::class, 'routing_operation_id');
    }

    public function dependsOnOperation(): BelongsTo
    {
        return $this->belongsTo(RoutingOperation::class, 'depends_on_operation_id');
    }
}
