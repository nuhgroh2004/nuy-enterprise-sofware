<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionProcess extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'code', 'name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function company() { return $this->belongsTo(Company::class); }
    public function workCenters() { return $this->hasMany(WorkCenter::class); }
}
