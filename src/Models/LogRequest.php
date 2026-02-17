<?php

namespace nplesa\observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    protected $table = 'observer_requests';

    protected $fillable = [
        'method',
        'url',
        'ip',
        'user_agent',
        'status',
        'user_id',
    ];

    public $timestamps = true;
}
