<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => null,

    'temporary_file_upload' => [
        'disk' => 'local',   // must be local
        'directory' => 'livewire-tmp',
        'middleware' => ['web'], // remove custom middlewares unless you added them intentionally
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
