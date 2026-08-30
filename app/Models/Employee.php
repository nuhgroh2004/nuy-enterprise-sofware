<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'plant_id', 'code', 'first_name', 'last_name', 'email',
        'phone', 'department', 'position', 'hire_date', 'is_active',
    ];

    protected $casts = ['hire_date' => 'date', 'is_active' => 'boolean'];

    public function company() { return $this->belongsTo(Company::class); }
    public function plant() { return $this->belongsTo(Plant::class); }
    public function workOrders() { return $this->hasMany(WorkOrder::class, 'operator_id'); }
    public function qualityInspections() { return $this->hasMany(QualityInspection::class, 'inspector_id'); }
}
