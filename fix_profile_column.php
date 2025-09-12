<?php

// Add profile picture column using raw SQL
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Forcefully add the column regardless of existing checks
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) NULL');
    echo "Command executed to add profile_picture column.\n";
    
    // Now check if the column exists
    $hasColumn = \Illuminate\Support\Facades\DB::select("
        SELECT COUNT(*) as count
        FROM information_schema.columns 
        WHERE table_schema = DATABASE()
        AND table_name = 'users' 
        AND column_name = 'profile_picture'
    ");
    
    echo "Column check result: " . ($hasColumn[0]->count > 0 ? "Column exists" : "Column does not exist") . "\n";
    
    // Also add to migrations table to prevent future migration attempts
    if (!(\Illuminate\Support\Facades\DB::table('migrations')->where('migration', '2025_09_12_000000_add_profile_picture_to_users_table')->exists())) {
        \Illuminate\Support\Facades\DB::table('migrations')->insert([
            'migration' => '2025_09_12_000000_add_profile_picture_to_users_table',
            'batch' => (\Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?? 0) + 1
        ]);
        echo "Migration record added to migrations table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}