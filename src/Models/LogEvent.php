<?php

namespace nplesa\observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogEvent extends Model
{
    protected $table = 'observer_events';

    protected $fillable = [
        'event_class',
        'payload',
        'user_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
