<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'entity_type', 'code', 'label', 'color',
        'sort_order', 'is_default', 'is_terminal', 'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_default' => 'boolean',
        'is_terminal' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
