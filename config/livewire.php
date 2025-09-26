<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => null,

    // Force local disk for temp uploads to avoid browser direct S3/B2 calls
    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_TMP_DISK', 'public'),
        'directory' => 'livewire-tmp',
        'middleware' => 'web',
        'preview_mimes' => ['image/jpeg','image/png','image/gif','image/webp'],
        'rules' => ['max:5120'], // 5 MB
        'max_upload_time' => 5,  // minutes
    ],

    // Other defaults left as-is for brevity
];
