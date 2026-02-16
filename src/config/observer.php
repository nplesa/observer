<?php

return [

    'log_requests' => [
        'enabled' => true,
        'exclude_routes' => [
            'admin/*',
            'api/docs/*',
        ],
        'ignore_methods' => [
            'OPTIONS',
            'HEAD',
        ],
        'rules' => [
            'methods' => ['POST', 'PUT', 'DELETE'],
            'only_authenticated' => true,
        ],
    ],

    'log_models' => [
        'enabled' => true,

        'only' => [
            // App\Models\User::class,
        ],
        'events' => ['created', 'updated', 'deleted', 'restored'],
        'log_only_dirty' => true,
    ],
    'log_db_actions' => true,
    'log_events' => true,
    'log_jobs' => true,
    'log_models' => true,

];
