<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'product_type_id',
        'product_category_id',
        'uom_id',
        'code',
        'name',
        'description',
        'barcode',
        'sku',
        'is_purchasable',
        'is_sellable',
        'is_manufacturable',
        'is_stockable',
        'is_batch_tracked',
        'is_serial_tracked',
        'is_expiry_tracked',
        'standard_cost',
        'average_cost',
        'last_purchase_cost',
        'min_stock',
        'max_stock',
        'reorder_point',
        'safety_stock',
        'lead_time_days',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_purchasable' => 'boolean',
        'is_sellable' => 'boolean',
        'is_manufacturable' => 'boolean',
        'is_stockable' => 'boolean',
        'is_batch_tracked' => 'boolean',
        'is_serial_tracked' => 'boolean',
        'is_expiry_tracked' => 'boolean',
        'standard_cost' => 'decimal:4',
        'average_cost' => 'decimal:4',
        'last_purchase_cost' => 'decimal:4',
        'min_stock' => 'decimal:4',
        'max_stock' => 'decimal:4',
        'reorder_point' => 'decimal:4',
        'safety_stock' => 'decimal:4',
        'lead_time_days' => 'integer',
    ];

    /* ============================================================
     | RELATIONSHIPS
     ============================================================ */

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productUoms(): HasMany
    {
        return $this->hasMany(ProductUom::class);
    }

    public function bomHeaders(): HasMany
    {
        return $this->hasMany(BomHeader::class);
    }

    public function routingHeaders(): HasMany
    {
        return $this->hasMany(RoutingHeader::class);
    }

    public function bomComponents(): HasMany
    {
        return $this->hasMany(BomComponent::class);
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(StockBalance::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function materialConsumptions(): HasMany
    {
        return $this->hasMany(MaterialConsumption::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /* ============================================================
     | SCOPES
     ============================================================ */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        $search = "%{$term}%";

        return $query->where(function ($q) use ($search) {
            $driver = \Illuminate\Support\Facades\DB::getDriverName();
            if ($driver === 'sqlite') {
                $q->whereRaw("LOWER(code) LIKE LOWER(?)", [$search])
                    ->orWhereRaw("LOWER(name) LIKE LOWER(?)", [$search])
                    ->orWhereRaw("LOWER(sku) LIKE LOWER(?)", [$search])
                    ->orWhereRaw("LOWER(barcode) LIKE LOWER(?)", [$search]);
            } else {
                $q->where('code', 'ilike', $search)
                    ->orWhere('name', 'ilike', $search)
                    ->orWhere('sku', 'ilike', $search)
                    ->orWhere('barcode', 'ilike', $search);
            }
        });
    }

    /* ============================================================
     | HELPERS
     ============================================================ */

    public function isUsedInTransactions(): bool
    {
        return $this->bomComponents()->exists()
            || $this->productionOrders()->whereIn('status', ['draft', 'planned', 'released', 'in_progress'])->exists()
            || $this->stockBalances()->where('quantity', '>', 0)->exists()
            || $this->materialConsumptions()->exists();
    }

    public function canBeDeleted(): bool
    {
        return !$this->isUsedInTransactions();
    }

    public function getActiveVariantsCountAttribute(): int
    {
        return $this->variants()->where('is_active', true)->count();
    }
}
