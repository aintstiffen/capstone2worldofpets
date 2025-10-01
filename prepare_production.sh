#!/bin/bash
# Script to prepare and optimize Laravel application for production deployment

# Print colorful messages
function print_message {
  echo -e "\e[1;34m$1\e[0m"
}

# Exit on error
set -e

print_message "👉 Starting production deployment preparation..."

# Copy production environment file
print_message "👉 Setting up production environment..."
cp .env.production .env
print_message "✅ Environment file configured"

# Install dependencies (if needed)
if [ "$1" == "--dependencies" ]; then
  print_message "👉 Installing composer dependencies..."
  composer install --no-dev --optimize-autoloader
  
  print_message "👉 Installing npm dependencies and building assets..."
  npm ci
  npm run build
  print_message "✅ Dependencies installed"
fi

# Optimize the application
print_message "👉 Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
print_message "✅ Application optimized"

# Run migrations if requested
if [ "$1" == "--migrate" ] || [ "$2" == "--migrate" ]; then
  print_message "👉 Running database migrations..."
  php artisan migrate --force
  print_message "✅ Migrations completed"
fi

print_message "🎉 Production deployment preparation completed successfully!"
print_message "🔍 Don't forget to verify HTTPS configuration after deployment!"