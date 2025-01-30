<?php

return [
    'api_url' => env('PUT_API_URL', 'https://put.sh/api/translate'),
    'api_key' => env('PUT_API_KEY', ''),
    'output_format' => env('PUT_OUTPUT_FORMAT', 'json'),
    'output_path' => resource_path('lang'),
    'scan_directories' => [
        'app',
        'resources',
    ],
];