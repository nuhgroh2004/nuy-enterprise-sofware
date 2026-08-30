<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionCalendar extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'code', 'name', 'description', 'is_default', 'is_active'];
    protected $casts = ['is_default' => 'boolean', 'is_active' => 'boolean'];

    public function company() { return $this->belongsTo(Company::class); }
    public function exceptions() { return $this->hasMany(CalendarException::class); }
}
