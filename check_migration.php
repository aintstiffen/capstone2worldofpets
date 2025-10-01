<?php

// Check migration status
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Check if the column exists
    if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'profile_picture')) {
        echo "Profile picture column EXISTS in the users table.\n";
    } else {
        echo "Profile picture column DOES NOT EXIST in the users table.\n";
    }
    
    // Check if our migration is in the migrations table
    $migration = \Illuminate\Support\Facades\DB::table('migrations')
        ->where('migration', 'like', '%profile_picture%')
        ->first();
    
    if ($migration) {
        echo "Migration found in migrations table: {$migration->migration}\n";
    } else {
        echo "No profile picture migration found in migrations table.\n";
        
        // Get all migrations
        echo "List of migrations in the database:\n";
        $migrations = \Illuminate\Support\Facades\DB::table('migrations')->get();
        foreach ($migrations as $m) {
            echo "- {$m->migration}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}