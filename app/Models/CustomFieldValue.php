<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_field_definition_id', 'entity_id', 'value',
    ];

    public function customFieldDefinition()
    {
        return $this->belongsTo(CustomFieldDefinition::class);
    }
}
