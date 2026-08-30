<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BomComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_version_id',
        'product_id',
        'uom_id',
        'quantity',
        'scrap_percentage',
        'yield_percentage',
        'is_fixed_quantity',
        'is_critical',
        'operation_sequence',
        'backflush',
        'is_optional',
        'alternative_group',
        'substitute_policy',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:6',
        'scrap_percentage' => 'decimal:2',
        'yield_percentage' => 'decimal:2',
        'is_fixed_quantity' => 'boolean',
        'is_critical' => 'boolean',
        'backflush' => 'boolean',
        'is_optional' => 'boolean',
        'sort_order' => 'integer',
        'operation_sequence' => 'integer',
    ];

    protected $table = 'bom_components';

    public function bomVersion(): BelongsTo
    {
        return $this->belongsTo(BomVersion::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function substitutes(): HasMany
    {
        return $this->hasMany(BomSubstitute::class)->orderBy('priority');
    }
}
