<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BomVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_header_id',
        'routing_version_id',
        'version',
        'revision',
        'effective_date',
        'expiry_date',
        'output_qty',
        'output_uom_id',
        'yield_percent',
        'approval_state',
        'approved_by',
        'approved_at',
        'submitted_by',
        'submitted_at',
        'notes',
        'is_default',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'output_qty' => 'decimal:6',
        'yield_percent' => 'decimal:2',
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    protected $table = 'bom_versions';

    public function bomHeader(): BelongsTo
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function routingVersion(): BelongsTo
    {
        return $this->belongsTo(RoutingVersion::class);
    }

    public function outputUom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'output_uom_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(BomComponent::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function isDraft(): bool
    {
        return $this->approval_state === 'draft';
    }

    public function isPending(): bool
    {
        return $this->approval_state === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->approval_state === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->approval_state === 'rejected';
    }
}
