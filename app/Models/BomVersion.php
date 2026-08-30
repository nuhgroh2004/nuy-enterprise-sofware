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

    protected $table = 'bom_versions';

    public function bomHeader(): BelongsTo
    {
        return $this->belongsTo(BomHeader::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(BomComponent::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }
}
