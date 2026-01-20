<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🔍 Checking Diet Options...\n\n";

$pets = Pet::whereNotNull('diet_images')->get();

if ($pets->isEmpty()) {
    echo "❌ No pets with diet options found.\n";
    exit(0);
}

echo "📊 Found {$pets->count()} pets with diet options:\n\n";

foreach ($pets as $pet) {
    $dietCount = is_array($pet->diet_images) ? count($pet->diet_images) : 0;
    echo "✓ {$pet->name} ({$pet->category}): {$dietCount} diet items\n";
    
    if ($dietCount > 0) {
        foreach ($pet->diet_images as $index => $diet) {
            $name = $diet['name'] ?? 'Unknown';
            $image = $diet['image'] ?? 'N/A';
            $hasUrl = filter_var($image, FILTER_VALIDATE_URL) ? '🌐 URL' : '📁 File';
            echo "   " . ($index + 1) . ". {$name} - {$hasUrl}\n";
        }
    }
    echo "\n";
}
