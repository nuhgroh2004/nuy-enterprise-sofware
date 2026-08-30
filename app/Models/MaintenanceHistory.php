<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'maintenance_order_id', 'machine_id', 'technician_id',
        'maintenance_type', 'description', 'performed_actions', 'parts_replaced',
        'actual_duration_hours', 'actual_cost', 'result', 'performed_date',
    ];

    protected $casts = [
        'actual_duration_hours' => 'decimal:2',
        'actual_cost' => 'decimal:4',
        'performed_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function maintenanceOrder()
    {
        return $this->belongsTo(MaintenanceOrder::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function technician()
    {
        return $this->belongsTo(Employee::class, 'technician_id');
    }
}
