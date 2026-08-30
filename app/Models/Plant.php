<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'code', 'name', 'address', 'city', 'phone', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function warehouses() { return $this->hasMany(Warehouse::class); }
    public function workCenters() { return $this->hasMany(WorkCenter::class); }
    public function machines() { return $this->hasMany(Machine::class); }
    public function employees() { return $this->hasMany(Employee::class); }
    public function productionOrders() { return $this->hasMany(ProductionOrder::class); }
}
