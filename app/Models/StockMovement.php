<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'product_id', 'warehouse_id', 'location_id', 'uom_id',
        'reason_code_id', 'document_number', 'movement_type', 'quantity', 'unit_cost',
        'total_cost', 'batch_number', 'serial_number', 'transaction_date',
        'source_type', 'source_id', 'reference_number', 'created_by_user_id', 'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'total_cost' => 'decimal:4',
        'transaction_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location()
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function reasonCode()
    {
        return $this->belongsTo(ReasonCode::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_user_id');
    }

    public function source()
    {
        if (!$this->source_type || !$this->source_id) {
            return null;
        }

        return $this->morphTo();
    }
}
