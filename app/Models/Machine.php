<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'plant_id', 'work_center_id', 'code', 'name', 'model',
        'serial_number', 'capacity_per_hour', 'purchase_date', 'warranty_expiry',
        'status', 'is_active',
    ];

    protected $casts = [
        'capacity_per_hour' => 'decimal:4',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'is_active' => 'boolean',
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function plant() { return $this->belongsTo(Plant::class); }
    public function workCenter() { return $this->belongsTo(WorkCenter::class); }
    public function workOrders() { return $this->hasMany(WorkOrder::class); }
    public function maintenanceHistories() { return $this->hasMany(MaintenanceHistory::class); }
    public function downtimeRecords() { return $this->hasMany(DowntimeRecord::class); }
}
