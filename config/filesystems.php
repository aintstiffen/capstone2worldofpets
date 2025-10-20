<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | The default disk Laravel will use. You can override this with the
    | FILESYSTEM_DISK environment variable in your .env file.
    |
    */

    'default' => env('FILESYSTEM_DISK', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Configure as many disks as needed. Laravel supports local and various
    | cloud-based disks. You can add more if needed.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // ✅ Cloudflare R2 Disk
        'r2' => [
            'driver' => 's3',
            'key' => env('R2_ACCESS_KEY_ID'),
            'secret' => env('R2_SECRET_ACCESS_KEY'),
            'region' => 'auto', // R2 doesn't require a specific region
            'bucket' => env('R2_BUCKET'),
            'endpoint' => env('R2_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
        ],

        // ✅ AWS S3 Disk
       's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-2'),
    'bucket' => env('AWS_BUCKET', 'worldofpetss'),
    'url' => env('AWS_URL', 'https://worldofpetss.s3.amazonaws.com'),
    'visibility' => 'public',
    'options' => [
        'ACL' => 'public-read',
    ],
],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure symbolic links for storage:link to work.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
