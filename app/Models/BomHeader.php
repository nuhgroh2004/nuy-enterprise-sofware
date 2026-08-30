<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BomHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'product_id',
        'code',
        'name',
        'description',
        'production_process_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $table = 'bom_headers';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionProcess(): BelongsTo
    {
        return $this->belongsTo(ProductionProcess::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(BomVersion::class);
    }

    public function activeVersion(): HasOne
    {
        return $this->hasOne(BomVersion::class)->where('is_default', true);
    }
}
