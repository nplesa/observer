<?php

namespace nplesa\observer\Models;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = 'observer_models';

    protected $fillable = [
        'model_type',
        'model_id',
        'event',
        'old_values',
        'new_values',
        'user_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('observer.connection')) {
            $this->setConnection($connection);
        }
    }
}
