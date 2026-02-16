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
        'queue' => true, // dacă false, scrie direct
        'only' => [],
        'events' => ['created','updated','deleted','restored'],
        'log_only_dirty' => true,
    ],
    'log_events' => true,
    'log_jobs' => true,
];
