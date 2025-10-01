<?php

/**
 * Script to clear all Laravel caches after S3 configuration changes
 */

// Check if we're in the correct directory
if (!file_exists('artisan')) {
    echo "Please run this script from your project root directory!\n";
    exit(1);
}

echo "Clearing Laravel caches...\n\n";

// Clear configuration cache
echo "1. Clearing configuration cache...\n";
passthru('php artisan config:clear');

// Clear route cache
echo "\n2. Clearing route cache...\n";
passthru('php artisan route:clear');

// Clear view cache
echo "\n3. Clearing view cache...\n";
passthru('php artisan view:clear');

// Clear application cache
echo "\n4. Clearing application cache...\n";
passthru('php artisan cache:clear');

// Clear compiled classes
echo "\n5. Clearing compiled class files...\n";
passthru('php artisan optimize:clear');

echo "\n✅ All caches have been cleared!\n";
echo "Please try uploading images in Filament admin panel now.\n";