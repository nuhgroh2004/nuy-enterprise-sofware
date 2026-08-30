<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'machine_id', 'work_center_id', 'code', 'name', 'frequency',
        'interval_value', 'hours_threshold', 'last_performed_date', 'next_due_date',
        'estimated_duration_hours', 'estimated_cost', 'is_active',
    ];

    protected $casts = [
        'interval_value' => 'integer',
        'hours_threshold' => 'integer',
        'last_performed_date' => 'date',
        'next_due_date' => 'date',
        'estimated_duration_hours' => 'decimal:2',
        'estimated_cost' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function workCenter()
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function maintenanceOrders()
    {
        return $this->hasMany(MaintenanceOrder::class);
    }
}
