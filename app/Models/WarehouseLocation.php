<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'warehouse_id', 'code', 'name', 'zone', 'aisle',
        'rack', 'shelf', 'bin', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function stockBalances() { return $this->hasMany(StockBalance::class); }
}
