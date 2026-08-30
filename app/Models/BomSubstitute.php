<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomSubstitute extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_component_id',
        'product_id',
        'uom_id',
        'conversion_factor',
        'is_preferred',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:6',
        'is_preferred' => 'boolean',
    ];

    protected $table = 'bom_substitutes';

    public function bomComponent(): BelongsTo
    {
        return $this->belongsTo(BomComponent::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }
}
