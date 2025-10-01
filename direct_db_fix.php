<?php

// Get database configuration from Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get database configuration
$config = config('database.connections.mysql');

// Create PDO connection
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};port={$config['port']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Check if column exists
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                         WHERE TABLE_SCHEMA = '{$config['database']}' 
                         AND TABLE_NAME = 'users' 
                         AND COLUMN_NAME = 'profile_picture'");
    $columnExists = (bool)$stmt->fetch();
    
    echo "Column exists: " . ($columnExists ? "Yes" : "No") . "\n";
    
    // Add column if it doesn't exist
    if (!$columnExists) {
        echo "Adding profile_picture column...\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL");
        echo "Column added successfully!\n";
    }
    
    // Mark migration as completed
    $stmt = $pdo->query("SELECT migration FROM migrations WHERE migration = '2025_09_12_000000_add_profile_picture_to_users_table'");
    $migrationExists = (bool)$stmt->fetch();
    
    if (!$migrationExists) {
        echo "Recording migration in migrations table...\n";
        
        // Get the latest batch number
        $stmt = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextBatch = ($result['max_batch'] ?? 0) + 1;
        
        // Insert the migration record
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute(['2025_09_12_000000_add_profile_picture_to_users_table', $nextBatch]);
        
        echo "Migration recorded.\n";
    }
    
    echo "All operations completed successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}