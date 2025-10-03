#!/usr/bin/env php
<?php

/**
 * Interactive Hotspots - Verification Script
 * 
 * This script checks if all necessary files and configurations are in place
 * for the interactive hotspots feature.
 */

echo "🔍 Verifying Interactive Hotspots Implementation...\n\n";

$baseDir = __DIR__;
$errors = [];
$warnings = [];
$success = [];

// Check 1: Required Files
echo "📁 Checking Required Files...\n";
$requiredFiles = [
    'app/Filament/Resources/PetResource.php' => 'Filament Resource',
    'resources/views/filament/components/image-preview.blade.php' => 'Image Preview Component',
    'app/Models/Pet.php' => 'Pet Model',
    'resources/views/dogs/show.blade.php' => 'Dog Show Page',
    'resources/views/cats/show.blade.php' => 'Cat Show Page',
];

foreach ($requiredFiles as $file => $description) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        $success[] = "✅ $description exists";
        echo "  ✅ $description\n";
    } else {
        $errors[] = "❌ $description not found: $file";
        echo "  ❌ $description\n";
    }
}

echo "\n";

// Check 2: Documentation Files
echo "📚 Checking Documentation...\n";
$docFiles = [
    'INTERACTIVE_HOTSPOTS_GUIDE.md',
    'IMPLEMENTATION_SUMMARY.md',
    'VISUAL_FLOW_DIAGRAM.md',
    'public/hotspots-demo.html',
];

foreach ($docFiles as $file) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        echo "  ✅ $file\n";
    } else {
        $warnings[] = "⚠️ Documentation file missing: $file";
        echo "  ⚠️ $file\n";
    }
}

echo "\n";

// Check 3: Model Configuration
echo "🔧 Checking Pet Model Configuration...\n";
$petModelPath = $baseDir . '/app/Models/Pet.php';
if (file_exists($petModelPath)) {
    $content = file_get_contents($petModelPath);
    
    // Check fillable array
    if (strpos($content, "'hotspots'") !== false && strpos($content, "'fun_facts'") !== false) {
        echo "  ✅ Fillable array includes hotspots and fun_facts\n";
        $success[] = "Pet model fillable array configured correctly";
    } else {
        $errors[] = "❌ Pet model fillable array missing hotspots or fun_facts";
        echo "  ❌ Fillable array missing hotspots or fun_facts\n";
    }
    
    // Check casts
    if (strpos($content, "'hotspots' => 'array'") !== false && strpos($content, "'fun_facts' => 'array'") !== false) {
        echo "  ✅ JSON casts configured for hotspots and fun_facts\n";
        $success[] = "Pet model casts configured correctly";
    } else {
        $errors[] = "❌ Pet model casts missing for hotspots or fun_facts";
        echo "  ❌ JSON casts missing\n";
    }
} else {
    $errors[] = "❌ Pet model file not found";
}

echo "\n";

// Check 4: Filament Resource Configuration
echo "🎨 Checking Filament Resource...\n";
$resourcePath = $baseDir . '/app/Filament/Resources/PetResource.php';
if (file_exists($resourcePath)) {
    $content = file_get_contents($resourcePath);
    
    if (strpos($content, 'Image Preview') !== false) {
        echo "  ✅ Image Preview section added\n";
        $success[] = "Image Preview section exists in PetResource";
    } else {
        $errors[] = "❌ Image Preview section not found in PetResource";
        echo "  ❌ Image Preview section not found\n";
    }
    
    if (strpos($content, 'image-preview') !== false) {
        echo "  ✅ image-preview view component referenced\n";
        $success[] = "View component reference exists";
    } else {
        $errors[] = "❌ image-preview view component not referenced";
        echo "  ❌ View component not referenced\n";
    }
    
    if (strpos($content, "Forms\Components\Repeater::make('hotspots')") !== false) {
        echo "  ✅ Hotspots repeater configured\n";
        $success[] = "Hotspots repeater exists";
    } else {
        $errors[] = "❌ Hotspots repeater not found";
        echo "  ❌ Hotspots repeater not found\n";
    }
    
    if (strpos($content, "Forms\Components\Repeater::make('fun_facts')") !== false) {
        echo "  ✅ Fun Facts repeater configured\n";
        $success[] = "Fun Facts repeater exists";
    } else {
        $errors[] = "❌ Fun Facts repeater not found";
        echo "  ❌ Fun Facts repeater not found\n";
    }
} else {
    $errors[] = "❌ PetResource file not found";
}

echo "\n";

// Check 5: View Component
echo "👁️ Checking Image Preview Component...\n";
$viewPath = $baseDir . '/resources/views/filament/components/image-preview.blade.php';
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    if (strpos($content, 'x-data') !== false) {
        echo "  ✅ Alpine.js integration found\n";
        $success[] = "Alpine.js integration in place";
    } else {
        $warnings[] = "⚠️ Alpine.js integration not found";
        echo "  ⚠️ Alpine.js integration not found\n";
    }
    
    if (strpos($content, 'addHotspot') !== false) {
        echo "  ✅ addHotspot function found\n";
        $success[] = "addHotspot function exists";
    } else {
        $errors[] = "❌ addHotspot function not found";
        echo "  ❌ addHotspot function not found\n";
    }
    
    if (strpos($content, 'removeHotspot') !== false) {
        echo "  ✅ removeHotspot function found\n";
        $success[] = "removeHotspot function exists";
    } else {
        $warnings[] = "⚠️ removeHotspot function not found";
        echo "  ⚠️ removeHotspot function not found\n";
    }
} else {
    $errors[] = "❌ Image preview component file not found";
}

echo "\n";

// Check 6: Database Migration
echo "🗄️ Checking Database Migration...\n";
$migrationDir = $baseDir . '/database/migrations';
if (is_dir($migrationDir)) {
    $files = scandir($migrationDir);
    $hotspotMigration = false;
    foreach ($files as $file) {
        if (strpos($file, 'add_hotspots_to_pets_table') !== false) {
            $hotspotMigration = true;
            echo "  ✅ Hotspots migration found: $file\n";
            $success[] = "Hotspots migration exists";
            break;
        }
    }
    if (!$hotspotMigration) {
        $warnings[] = "⚠️ Hotspots migration not found (may need to run migrations)";
        echo "  ⚠️ Hotspots migration not found\n";
    }
} else {
    $warnings[] = "⚠️ Migrations directory not found";
}

echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════\n";
echo "📊 VERIFICATION SUMMARY\n";
echo "═══════════════════════════════════════════════════════════\n\n";

echo "✅ Successes: " . count($success) . "\n";
echo "⚠️  Warnings: " . count($warnings) . "\n";
echo "❌ Errors: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "❌ ERRORS FOUND:\n";
    foreach ($errors as $error) {
        echo "  $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "  $warning\n";
    }
    echo "\n";
}

if (count($errors) === 0) {
    echo "🎉 SUCCESS! All critical components are in place!\n\n";
    echo "Next Steps:\n";
    echo "1. Run migrations if not already done:\n";
    echo "   php artisan migrate\n\n";
    echo "2. Test in Filament admin:\n";
    echo "   - Navigate to /admin/pets\n";
    echo "   - Create or edit a pet\n";
    echo "   - Add an image URL\n";
    echo "   - Expand 'Image Preview' section\n";
    echo "   - Click on image to add hotspots\n\n";
    echo "3. View demo:\n";
    echo "   - Open /hotspots-demo.html in browser\n\n";
    echo "4. Read documentation:\n";
    echo "   - INTERACTIVE_HOTSPOTS_GUIDE.md\n";
    echo "   - IMPLEMENTATION_SUMMARY.md\n";
    echo "   - VISUAL_FLOW_DIAGRAM.md\n\n";
} else {
    echo "⚠️  Please fix the errors above before proceeding.\n\n";
}

echo "═══════════════════════════════════════════════════════════\n";
