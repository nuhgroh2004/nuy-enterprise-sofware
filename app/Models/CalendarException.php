<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarException extends Model
{
    use HasFactory;

    protected $fillable = ['production_calendar_id', 'name', 'exception_date', 'type', 'is_working_day', 'start_time', 'end_time', 'notes'];
    protected $casts = ['exception_date' => 'date', 'is_working_day' => 'boolean', 'start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i'];

    public function productionCalendar() { return $this->belongsTo(ProductionCalendar::class); }
}
