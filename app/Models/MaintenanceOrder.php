<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'maintenance_schedule_id', 'machine_id', 'work_center_id',
        'assigned_to', 'document_number', 'maintenance_type', 'description', 'notes',
        'scheduled_date', 'started_at', 'completed_at', 'actual_duration_hours',
        'actual_cost', 'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'actual_duration_hours' => 'decimal:2',
        'actual_cost' => 'decimal:4',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function workCenter()
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class);
    }
}
