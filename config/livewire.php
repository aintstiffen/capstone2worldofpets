<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => null,

    // Temporary upload configuration for B2 Backblaze storage
    'temporary_file_upload' => [
        'disk' => 'public',
        'directory' => 'livewire-tmp',
        'middleware' => ['web', 'secure.upload', 'debug.upload'],
        'rules' => [
            'file',
            'max:5120', // 5MB
            'mimes:jpeg,png,gif,webp',
        ],
    ],

    'features' => [
        // 'ignore_csrf' => env('LIVEWIRE_IGNORE_CSRF', false),
    ],

    // Other defaults left as-is for brevity
];
