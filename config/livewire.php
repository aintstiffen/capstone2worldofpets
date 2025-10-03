<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => null,

    'temporary_file_upload' => [
        'disk' => 's3',
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'rules' => null, // This disables the MIME check
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'jpg', 'jpeg', 'webp'
        ],
        'max_upload_time' => 5,
    ],

    'features' => [
        // 'ignore_csrf' => env('LIVEWIRE_IGNORE_CSRF', false),
    ],
];