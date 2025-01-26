<?php

return [
    'environments' => [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default', 'seeding'],
                'balance' => 'auto',
                'maxProcesses' => 10,
                'tries' => 3,
                'timeout' => 120,
            ],
        ],
        'local' => [
            'supervisor-1' => [
                'maxProcesses' => 10, // Handle multiple jobs
            ],
        ],
    ],
];
