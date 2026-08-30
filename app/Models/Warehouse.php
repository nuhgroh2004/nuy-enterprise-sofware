<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'plant_id', 'code', 'name', 'type', 'address', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function plant() { return $this->belongsTo(Plant::class); }
    public function locations() { return $this->hasMany(WarehouseLocation::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function stockBalances() { return $this->hasMany(StockBalance::class); }
}
