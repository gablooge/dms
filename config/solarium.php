<?php

return [
    'endpoint' => [
        'localhost' => [
            'host' => env('SOLR_HOST', 'dms.samsulhadi.com'),
            'port' => env('SOLR_PORT', '80'),
            'path' => env('SOLR_PATH', '/'),
            'core' => env('SOLR_CORE', 'dms')
        ]
    ]
];