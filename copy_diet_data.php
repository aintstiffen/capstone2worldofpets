<?php

/**
 * Copy diet_images data from Akita to all other dog breeds
 * Run with: php copy_diet_data.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🐕 Copying Diet Data from Akita to All Dog Breeds...\n\n";

// Get Akita's diet data
$akita = Pet::where('name', 'Akita')->where('category', 'dog')->first();

if (!$akita) {
    echo "❌ Error: Akita breed not found in database!\n";
    exit(1);
}

if (empty($akita->diet_images) || !is_array($akita->diet_images)) {
    echo "❌ Error: Akita has no diet_images data!\n";
    echo "Current diet_images value: " . json_encode($akita->diet_images) . "\n";
    exit(1);
}

echo "✅ Found Akita with diet data:\n";
echo "   Akita ID: {$akita->id}\n";
echo "   Diet items count: " . count($akita->diet_images) . "\n\n";

echo "📋 Akita's Diet Data:\n";
foreach ($akita->diet_images as $index => $item) {
    if (is_array($item)) {
        $name = $item['name'] ?? 'Unknown';
        $image = $item['image'] ?? 'N/A';
        $description = $item['description'] ?? 'No description';
        echo "   " . ($index + 1) . ". {$name}\n";
        echo "      Image: {$image}\n";
        echo "      Description: " . substr($description, 0, 60) . "...\n\n";
    }
}

// Get all dog breeds except Akita
$dogBreeds = Pet::where('category', 'dog')
    ->where('id', '!=', $akita->id)
    ->get();

echo "📊 Found " . $dogBreeds->count() . " other dog breeds to update\n\n";

$confirm = readline("⚠️  This will copy Akita's diet data to {$dogBreeds->count()} breeds. Continue? (yes/no): ");

if (strtolower(trim($confirm)) !== 'yes') {
    echo "❌ Operation cancelled.\n";
    exit(0);
}

echo "\n🚀 Starting copy operation...\n\n";

$updatedCount = 0;
$skippedCount = 0;

foreach ($dogBreeds as $breed) {
    // Check if breed already has diet data
    if (!empty($breed->diet_images) && is_array($breed->diet_images) && count($breed->diet_images) > 0) {
        echo "⏭️  Skipped: {$breed->name} (already has diet data)\n";
        $skippedCount++;
        continue;
    }
    
    // Copy Akita's diet_images to this breed
    $breed->diet_images = $akita->diet_images;
    $breed->save();
    
    echo "✅ Updated: {$breed->name}\n";
    $updatedCount++;
}

echo "\n";
echo "═══════════════════════════════════════════════\n";
echo "📊 SUMMARY\n";
echo "═══════════════════════════════════════════════\n";
echo "✅ Updated: {$updatedCount} breeds\n";
echo "⏭️  Skipped: {$skippedCount} breeds (already had data)\n";
echo "📋 Total processed: " . ($updatedCount + $skippedCount) . " breeds\n";
echo "═══════════════════════════════════════════════\n";
echo "\n✨ Diet data copy complete!\n";
echo "\nYou can now view the diet options on any dog breed page.\n";
