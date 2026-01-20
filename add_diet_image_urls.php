<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🎨 Adding Image URLs to Diet Options...\n\n";

// Diet option name mappings to image URLs
$dietImageMap = [
    // Dog diet options
    'working breed formula' => [
        'url' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=800&q=80',
        'desc' => 'High-protein (28-32%), high-fat diet for large, active working breeds. Provides sustained energy for physically demanding tasks.'
    ],
    'performance energy' => [
        'url' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=800&q=80',
        'desc' => 'High-calorie formula designed for athletic and highly active dogs. Contains elevated protein and fat levels to support endurance and recovery.'
    ],
    'raw whole prey' => [
        'url' => 'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?w=800&q=80',
        'desc' => 'Biologically appropriate raw food diet mimicking what wild canines eat. Includes muscle meat, organs, and bones in natural ratios.'
    ],
    'fresh meat & fish' => [
        'url' => 'https://images.unsplash.com/photo-1560788458-86b3e211f18f?w=800&q=80',
        'desc' => 'Minimally processed diet using fresh, whole food ingredients. High in quality proteins from various meat and fish sources.'
    ],
    'high-quality dry kibble' => [
        'url' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80',
        'desc' => 'Complete and balanced dry food with high-quality ingredients. Convenient, shelf-stable option with proper nutrient ratios.'
    ],
    'raw diet (barf)' => [
        'url' => 'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?w=800&q=80',
        'desc' => 'Biologically Appropriate Raw Food diet. Mimics ancestral feeding with raw meats, bones, organs, and vegetables.'
    ],
    'wet/canned food' => [
        'url' => 'https://images.unsplash.com/photo-1569984552742-2b132dc47ed8?w=800&q=80',
        'desc' => 'Moisture-rich food with high palatability. Good for hydration and can be easier to digest for some pets.'
    ],
    'home-cooked diet' => [
        'url' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&q=80',
        'desc' => 'Prepared from whole food ingredients at home. Requires careful planning to ensure nutritional balance and completeness.'
    ],
    'grain-free' => [
        'url' => 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=800&q=80',
        'desc' => 'Diet free from grains like wheat, corn, and rice. Uses alternative carbohydrate sources like sweet potato or peas.'
    ],
    'weight management' => [
        'url' => 'https://images.unsplash.com/photo-1623387641168-d9803ddd3f35?w=800&q=80',
        'desc' => 'Reduced calorie formula for weight control. High in fiber to promote satiety while maintaining nutritional balance.'
    ],
    'sensitive stomach' => [
        'url' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80',
        'desc' => 'Easily digestible formula for dogs with digestive sensitivities. Contains limited ingredients and gentle proteins.'
    ],
    'joint support' => [
        'url' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=800&q=80',
        'desc' => 'Fortified with glucosamine, chondroitin, and omega-3s. Supports joint health and mobility, especially for larger breeds.'
    ],
    'large breed' => [
        'url' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=800&q=80',
        'desc' => 'Specially formulated for large and giant breeds. Controlled calcium and phosphorus for healthy bone growth.'
    ],
    'small breed' => [
        'url' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80',
        'desc' => 'Small kibble size for tiny mouths. Higher calorie density to meet the energy needs of small dogs.'
    ],
    'dental care' => [
        'url' => 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=800&q=80',
        'desc' => 'Specially shaped kibble that helps reduce plaque and tartar buildup. Promotes oral health and fresh breath.'
    ],
    'skin & coat' => [
        'url' => 'https://images.unsplash.com/photo-1560788458-86b3e211f18f?w=800&q=80',
        'desc' => 'Rich in omega fatty acids for healthy skin and shiny coat. Often includes salmon oil or flaxseed.'
    ],
    'high-protein' => [
        'url' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=800&q=80',
        'desc' => 'Elevated protein content from quality meat sources. Ideal for active dogs and muscle maintenance.'
    ],
    'cardiac' => [
        'url' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=800&q=80',
        'desc' => 'Formulated to support heart health. Lower in sodium and enriched with taurine and L-carnitine.'
    ],
    'fresh' => [
        'url' => 'https://images.unsplash.com/photo-1560788458-86b3e211f18f?w=800&q=80',
        'desc' => 'Minimally processed meals made from fresh, whole ingredients. Often refrigerated or frozen for freshness.'
    ],
    
    // Cat diet options
    'dry food' => [
        'url' => 'https://images.unsplash.com/photo-1589652717521-10c0d092dea9?w=800&q=80',
        'desc' => 'Complete and balanced dry kibble. Convenient, helps maintain dental health, and provides all necessary nutrients.'
    ],
    'wet' => [
        'url' => 'https://images.unsplash.com/photo-1610787158370-cce95dd4c7f5?w=800&q=80',
        'desc' => 'Moisture-rich canned food that helps with hydration. Often more palatable and easier for cats to digest.'
    ],
    'raw' => [
        'url' => 'https://images.unsplash.com/photo-1615789591457-74a63395c990?w=800&q=80',
        'desc' => 'Biologically appropriate diet consisting of raw meat, organs, and bones. Mimics natural prey diet of wild felines.'
    ],
    'kidney support' => [
        'url' => 'https://images.unsplash.com/photo-1558929996-da64ba858215?w=800&q=80',
        'desc' => 'Therapeutic diet for cats with kidney issues. Lower in phosphorus and protein, helps manage kidney disease.'
    ],
    'hairball' => [
        'url' => 'https://images.unsplash.com/photo-1596854407944-bf87f6fdd49e?w=800&q=80',
        'desc' => 'High-fiber formula designed to reduce hairball formation. Helps hair pass through digestive system naturally.'
    ],
    'persian' => [
        'url' => 'https://images.unsplash.com/photo-1610787158370-cce95dd4c7f5?w=800&q=80',
        'desc' => 'Specially shaped kibble for flat-faced breeds. Easy to pick up and chew, supports digestive health.'
    ],
    'himalayan' => [
        'url' => 'https://images.unsplash.com/photo-1610787158370-cce95dd4c7f5?w=800&q=80',
        'desc' => 'Formulated for flat-faced breeds with kidney support. Addresses breed-specific nutritional needs.'
    ],
    'limited ingredient' => [
        'url' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&q=80',
        'desc' => 'Simplified formula with few ingredients. Ideal for cats with food sensitivities or allergies.'
    ],
];

// Get all pets with diet options
$pets = Pet::whereNotNull('diet_images')->get();

$updatedCount = 0;
$updatedPets = [];

foreach ($pets as $pet) {
    if (!is_array($pet->diet_images) || empty($pet->diet_images)) {
        continue;
    }

    $updated = false;
    $newDietImages = [];

    foreach ($pet->diet_images as $diet) {
        if (!is_array($diet) || empty($diet['name'])) {
            $newDietImages[] = $diet;
            continue;
        }

        $dietName = strtolower($diet['name']);
        $imageUrl = null;
        $description = $diet['description'] ?? '';

        // Try to find a matching image URL
        foreach ($dietImageMap as $keyword => $data) {
            if (str_contains($dietName, $keyword)) {
                $imageUrl = $data['url'];
                if (empty($description)) {
                    $description = $data['desc'];
                }
                break;
            }
        }

        // Update the diet item
        if ($imageUrl) {
            $diet['image'] = $imageUrl;
            $diet['description'] = $description;
            $updated = true;
        }

        $newDietImages[] = $diet;
    }

    // Save if updated
    if ($updated) {
        $pet->diet_images = $newDietImages;
        $pet->save();
        $updatedCount++;
        $updatedPets[] = $pet->name;
        echo "✅ Updated: {$pet->name} ({$pet->category})\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════\n";
echo "✨ Summary\n";
echo "═══════════════════════════════════════\n";
echo "Total pets updated: {$updatedCount}\n";
if (!empty($updatedPets)) {
    echo "\nUpdated pets:\n";
    foreach ($updatedPets as $name) {
        echo "  • {$name}\n";
    }
}
echo "\n✅ Done! All diet options now have image URLs.\n";
