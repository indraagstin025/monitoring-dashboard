<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    protected $fillable = [
        'target_id',
        'status',
        'response_time_ms',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function target()
    {
        return $this->belongsTo(Target::class);
    }
}
