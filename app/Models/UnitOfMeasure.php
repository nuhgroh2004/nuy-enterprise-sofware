<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $table = 'units_of_measure';
    protected $fillable = ['code', 'name', 'symbol', 'category', 'decimal_places', 'is_active'];
    protected $casts = ['decimal_places' => 'integer', 'is_active' => 'boolean'];

    public function fromConversions() { return $this->hasMany(UomConversion::class, 'from_uom_id'); }
    public function toConversions() { return $this->hasMany(UomConversion::class, 'to_uom_id'); }
    public function products() { return $this->hasMany(Product::class, 'uom_id'); }
}
