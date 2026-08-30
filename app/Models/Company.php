<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'legal_name', 'address', 'city', 'state',
        'country', 'postal_code', 'phone', 'email', 'tax_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function plants() { return $this->hasMany(Plant::class); }
    public function warehouses() { return $this->hasMany(Warehouse::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function employees() { return $this->hasMany(Employee::class); }
    public function workCenters() { return $this->hasMany(WorkCenter::class); }
    public function productionOrders() { return $this->hasMany(ProductionOrder::class); }
}
