<?php

/**
 * Copy diet_images data from American Shorthair to all cat breeds
 * Run with: php copy_cat_diet_data.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🐱 Copy Diet Data from American Shorthair to All Cat Breeds...\n\n";

// Get American Shorthair's diet data
$americanShorthair = Pet::where('name', 'American Shorthair')->where('category', 'cat')->first();

if (!$americanShorthair) {
    echo "❌ Error: American Shorthair breed not found in database!\n";
    exit(1);
}

if (empty($americanShorthair->diet_images) || !is_array($americanShorthair->diet_images)) {
    echo "❌ Error: American Shorthair has no diet_images data!\n";
    echo "Current diet_images value: " . json_encode($americanShorthair->diet_images) . "\n";
    exit(1);
}

echo "✅ Found American Shorthair with diet data:\n";
echo "   American Shorthair ID: {$americanShorthair->id}\n";
echo "   Diet items count: " . count($americanShorthair->diet_images) . "\n\n";

echo "📋 American Shorthair's Diet Data:\n";
foreach ($americanShorthair->diet_images as $index => $item) {
    if (is_array($item)) {
        $name = $item['name'] ?? 'Unknown';
        $image = $item['image'] ?? 'N/A';
        $description = $item['description'] ?? 'No description';
        echo "   " . ($index + 1) . ". {$name}\n";
        echo "      Image: {$image}\n";
        echo "      Description: " . substr($description, 0, 60) . "...\n\n";
    }
}

// Get all cat breeds except American Shorthair
$catBreeds = Pet::where('category', 'cat')
    ->where('id', '!=', $americanShorthair->id)
    ->get();

echo "📊 Found " . $catBreeds->count() . " other cat breeds\n\n";

$confirm = readline("⚠️  This will OVERWRITE diet data for ALL {$catBreeds->count()} cat breeds. Continue? (yes/no): ");

if (strtolower(trim($confirm)) !== 'yes') {
    echo "❌ Operation cancelled.\n";
    exit(0);
}

echo "\n🚀 Starting copy operation...\n\n";

$updatedCount = 0;

foreach ($catBreeds as $breed) {
    // Copy American Shorthair's diet_images to this breed
    $breed->diet_images = $americanShorthair->diet_images;
    $breed->save();
    
    echo "✅ Updated: {$breed->name}\n";
    $updatedCount++;
}

echo "\n";
echo "═══════════════════════════════════════════════\n";
echo "📊 SUMMARY\n";
echo "═══════════════════════════════════════════════\n";
echo "✅ Updated: {$updatedCount} cat breeds\n";
echo "📋 Total processed: {$updatedCount} cat breeds\n";
echo "═══════════════════════════════════════════════\n";
echo "\n✨ Diet data copy complete!\n";
echo "\nAll cat breeds now have American Shorthair's diet data.\n";
