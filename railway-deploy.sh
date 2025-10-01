#!/bin/bash

# Railway deployment script
echo "Starting deployment..."

# Set production environment
export APP_ENV=production

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "Deployment completed!"