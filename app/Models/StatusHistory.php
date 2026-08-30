<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'statusable_type', 'statusable_id', 'from_status',
        'to_status', 'user_id', 'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }
}
