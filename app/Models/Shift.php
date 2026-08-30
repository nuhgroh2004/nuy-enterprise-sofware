<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'code', 'name', 'start_time', 'end_time', 'break_minutes', 'is_active'];
    protected $casts = ['start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i', 'break_minutes' => 'decimal:1', 'is_active' => 'boolean'];

    public function company() { return $this->belongsTo(Company::class); }
}
