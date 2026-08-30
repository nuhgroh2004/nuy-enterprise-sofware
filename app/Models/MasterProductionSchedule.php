<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'plant_id', 'document_number', 'plan_date', 'from_date',
        'to_date', 'status', 'notes',
    ];

    protected $casts = [
        'plan_date' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    protected $table = 'master_production_schedules';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function lines()
    {
        return $this->hasMany(MpsLine::class);
    }
}
