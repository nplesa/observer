<?php

namespace nplesa\Observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogJob extends Model
{
    protected $table = 'log_jobs';

    protected $fillable = [
        'job_class',
        'payload',
        'user_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
