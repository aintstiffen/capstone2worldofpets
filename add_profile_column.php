<?php

// Fix profile picture column issue
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'profile_picture')) {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL');
        echo "Profile picture column added successfully.\n";
    } else {
        echo "Profile picture column already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}