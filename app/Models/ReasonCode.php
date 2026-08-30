<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonCode extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'code', 'name', 'category', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function company() { return $this->belongsTo(Company::class); }
    public function scraps() { return $this->hasMany(Scrap::class, 'reason_code_id'); }
    public function downtimeRecords() { return $this->hasMany(DowntimeRecord::class, 'reason_code_id'); }
    public function stockMovements() { return $this->hasMany(StockMovement::class, 'reason_code_id'); }
}
