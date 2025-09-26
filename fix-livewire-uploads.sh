#!/bin/bash
# Fix Livewire Upload Issues Script

echo "Fixing Livewire file upload configuration..."

# Clear all Laravel caches
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

# Create storage link if it doesn't exist
php artisan storage:link

# Create livewire temp directory
mkdir -p storage/app/public/livewire-tmp
chmod -R 775 storage/app/public/livewire-tmp

# Set proper permissions for storage directories
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "Livewire upload configuration has been fixed!"
echo "Please try uploading a file now."