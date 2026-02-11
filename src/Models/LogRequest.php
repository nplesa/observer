<?php

namespace nplesa\Observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    protected $table = 'log_requests';

    protected $fillable = [
        'method',
        'url',
        'ip',
        'user_agent',
        'status',
    ];

    protected $timestamps = true;
}
