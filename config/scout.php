<?php

return [
    'driver' => env('SCOUT_DRIVER', 'elasticsearch'),

    'elasticsearch' => [
        'hosts' => [
            env('ELASTICSEARCH_HOST', 'localhost') . ':' . env('ELASTICSEARCH_PORT', '9200'),
        ],
    ],
];
