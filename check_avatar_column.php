<?php

// Check if avatar_style column exists in users table
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$result = $db->query("PRAGMA table_info(users)");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);
$hasAvatarStyle = false;

echo "Users table columns:\n";
foreach ($columns as $column) {
    echo $column['name'] . ' (' . $column['type'] . ")\n";
    if ($column['name'] === 'avatar_style') {
        $hasAvatarStyle = true;
    }
}

if (!$hasAvatarStyle) {
    echo "\nADDING MISSING COLUMN: avatar_style\n";
    // Add the missing column
    $db->exec('ALTER TABLE users ADD COLUMN avatar_style VARCHAR(255) NULL');
}

// Verify the column was added
$result = $db->query("PRAGMA table_info(users)");
$columnsAfter = $result->fetchAll(PDO::FETCH_ASSOC);
$hasAvatarStyleAfter = false;

echo "\nAfter check - Users table columns:\n";
foreach ($columnsAfter as $column) {
    echo $column['name'] . ' (' . $column['type'] . ")\n";
    if ($column['name'] === 'avatar_style') {
        $hasAvatarStyleAfter = true;
    }
}

if ($hasAvatarStyleAfter) {
    echo "\nThe avatar_style column exists or was successfully added.\n";
} else {
    echo "\nERROR: Failed to add avatar_style column.\n";
}

// Test setting a value
if ($hasAvatarStyleAfter) {
    $result = $db->query("SELECT id FROM users LIMIT 1");
    $firstUser = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($firstUser) {
        $testValue = 'pixel-art';
        $stmt = $db->prepare("UPDATE users SET avatar_style = ? WHERE id = ?");
        $stmt->execute([$testValue, $firstUser['id']]);
        
        $result = $db->query("SELECT avatar_style FROM users WHERE id = " . $firstUser['id']);
        $updatedUser = $result->fetch(PDO::FETCH_ASSOC);
        
        echo "\nTest update for user ID " . $firstUser['id'] . ":\n";
        echo "Set value: $testValue\n";
        echo "Retrieved value: " . ($updatedUser['avatar_style'] ?? 'NULL') . "\n";
        echo "Update " . ($updatedUser['avatar_style'] === $testValue ? 'SUCCESSFUL' : 'FAILED') . "\n";
    } else {
        echo "\nNo users found for testing.\n";
    }
}