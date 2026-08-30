<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quality_inspection_id', 'parameter_name', 'specification', 'actual_value',
        'unit', 'result', 'notes', 'sort_order',
    ];

    public function qualityInspection()
    {
        return $this->belongsTo(QualityInspection::class);
    }
}
