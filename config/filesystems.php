<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),
    'disks' => [
        'local' => ['driver' => 'local', 'root' => storage_path('app'), 'throw' => false],
        'public' => ['driver' => 'local', 'root' => storage_path('app/public'), 'url' => env('APP_URL').'/storage', 'visibility' => 'public', 'throw' => false],
        's3' => ['driver' => 's3', 'bucket' => env('AWS_BUCKET'), 'region' => env('AWS_DEFAULT_REGION'), 'url' => env('AWS_URL'), 'endpoint' => env('AWS_ENDPOINT'), 'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false)],
    ],
    'links' => [public_path('storage') => storage_path('app/public')],
];