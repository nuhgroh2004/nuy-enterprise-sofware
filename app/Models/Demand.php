<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'plant_id', 'document_number', 'source_type', 'source_id',
        'source_number', 'demand_date', 'required_date', 'priority', 'status', 'notes',
    ];

    protected $casts = [
        'demand_date' => 'date',
        'required_date' => 'date',
    ];

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
        return $this->hasMany(DemandLine::class);
    }

    public function mpsLines()
    {
        return $this->hasMany(MpsLine::class);
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class);
    }
}
