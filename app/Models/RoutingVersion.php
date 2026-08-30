<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutingVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'routing_header_id',
        'version',
        'effective_date',
        'expiry_date',
        'approval_state',
        'approved_by',
        'approved_at',
        'notes',
        'is_default',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    public function routingHeader(): BelongsTo
    {
        return $this->belongsTo(RoutingHeader::class);
    }

    public function operations(): HasMany
    {
        return $this->hasMany(RoutingOperation::class)->orderBy('sequence');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }
}
