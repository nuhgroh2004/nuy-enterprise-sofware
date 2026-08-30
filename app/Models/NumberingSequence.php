<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumberingSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'document_type', 'prefix', 'plant_code', 'include_year',
        'include_month', 'padding', 'current_sequence', 'is_active',
    ];

    protected $casts = [
        'include_year' => 'boolean',
        'include_month' => 'boolean',
        'padding' => 'integer',
        'current_sequence' => 'integer',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function nextNumber(): string
    {
        $this->increment('current_sequence');

        $sequence = str_pad($this->current_sequence, $this->padding, '0', STR_PAD_LEFT);

        $parts = [];

        if ($this->prefix) {
            $parts[] = $this->prefix;
        }

        if ($this->plant_code) {
            $parts[] = $this->plant_code;
        }

        if ($this->include_year) {
            $parts[] = date('Y');
        }

        if ($this->include_month) {
            $parts[] = date('m');
        }

        $parts[] = $sequence;

        return implode('-', $parts);
    }
}
