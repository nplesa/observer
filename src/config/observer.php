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
    'log_db_actions' => true,
    'log_events' => true,
    'log_jobs' => true,
    'log_models' => true,

];
