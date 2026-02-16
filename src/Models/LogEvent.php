<?php

namespace nplesa\Observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogEvent extends Model
{
    protected $table = 'log_events';

    protected $fillable = [
        'event_class',
        'payload',
        'user_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
