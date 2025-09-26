<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => null,

    // Temporary upload configuration for B2 Backblaze storage
    'temporary_file_upload' => [
        'disk' => 'public', // Use public disk for temporary uploads, then move to B2
        'directory' => 'livewire-tmp',
        'middleware' => ['web', 'filament.auth', 'secure.upload'],
        'preview_mimes' => [
            'image/jpeg',
            'image/png', 
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ],
        'rules' => [
            'required',
            'file',
            'max:' . env('LIVEWIRE_MAX_UPLOAD_SIZE', 5120), // 5MB default, configurable
            'mimes:jpeg,png,gif,webp,svg',
            'dimensions:max_width=4000,max_height=4000', // Prevent extremely large images
        ],
        'max_upload_time' => env('LIVEWIRE_UPLOAD_TIMEOUT', 30), // 30 seconds default
    ],

    'features' => [
        // 'ignore_csrf' => env('LIVEWIRE_IGNORE_CSRF', false),
    ],

    // Other defaults left as-is for brevity
];
