<?php

return [

    'log_requests' => [
        'enabled' => true,
        'exclude_routes' => ['admin/*', 'api/docs/*'],
        'ignore_methods' => ['OPTIONS', 'HEAD'],
        'rules' => [
            'methods' => ['POST', 'PUT', 'DELETE'],
            'only_authenticated' => true,
        ],
    ],
    'log_models' => [
        'enabled' => true,
        'queue' => true,  // async
        'only' => [
            // App\Models\User::class
        ],
        'events' => ['created','updated','deleted','restored'],
        'log_only_dirty' => true,
    ],
    'log_events' => [
        'enabled' => true,
        'queue' => true,  // async
        'only' => [
            // App\Events\SomeEvent::class
        ],
        'ignore' => [
            'Illuminate\\Log\\Events\\MessageLogged',
        ],
    ],
    'log_jobs' => [
        'enabled' => true,
        'queue' => true,  // async
        'only' => [
            // App\Jobs\SomeJob::class
        ],
    ],

    'connection' => null, // other DB connection
];