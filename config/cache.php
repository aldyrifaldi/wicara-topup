<?php

return [
    'default' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),
    'stores' => [
        'array' => ['driver' => 'array', 'serialize' => false],
        'database' => ['driver' => 'database', 'table' => env('DB_CACHE_TABLE', 'cache'), 'connection' => env('DB_CACHE_CONNECTION'), 'lock_connection' => env('DB_CACHE_LOCK_CONNECTION')],
        'file' => ['driver' => 'file', 'path' => storage_path('framework/cache/data'), 'lock_path' => storage_path('framework/cache/data')],
        'redis' => ['driver' => 'redis', 'connection' => env('REDIS_CACHE_CONNECTION', 'cache'), 'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'cache')],
        'dynamodb' => ['driver' => 'dynamodb', 'key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'), 'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), 'table' => env('DYNAMODB_CACHE_TABLE', 'cache'), 'endpoint' => env('DYNAMODB_ENDPOINT')],
    ],
    'prefix' => env('CACHE_PREFIX', 'wicara_cache'),
];