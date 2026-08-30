<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'machine_id', 'work_order_id', 'maintenance_order_id',
        'reason_code_id', 'downtime_type', 'started_at', 'ended_at',
        'duration_minutes', 'description',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function maintenanceOrder()
    {
        return $this->belongsTo(MaintenanceOrder::class);
    }

    public function reasonCode()
    {
        return $this->belongsTo(ReasonCode::class);
    }
}
