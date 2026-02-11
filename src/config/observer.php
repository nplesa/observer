<?php
return [
    'log_requests' => [
        'enabled' => true,
        // Rute care nu se loghează
        'exclude_routes' => [
            'admin/*',
            'api/docs/*',
        ]
    ],
    'log_db_actions' => true,
    'log_events' => true,
    'log_jobs' => true,
    'log_models' => true,

];

