<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🔍 Checking Alaskan Malamute Diet Options...\n\n";

$pet = Pet::where('name', 'Alaskan Malamute')->first();

if (!$pet) {
    echo "❌ Pet not found\n";
    exit(1);
}

echo "Pet: {$pet->name}\n";
echo "Category: {$pet->category}\n";
echo "Diet Items: " . count($pet->diet_images) . "\n\n";

foreach ($pet->diet_images as $index => $diet) {
    echo "Diet " . ($index + 1) . ":\n";
    echo "  Name: {$diet['name']}\n";
    echo "  Image: {$diet['image']}\n";
    echo "  Description: " . substr($diet['description'], 0, 100) . "...\n\n";
}
