<?php

namespace nplesa\observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogJob extends Model
{
    protected $table = 'observer_jobs';

    protected $fillable = [
        'job_class',
        'payload',
        'user_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
