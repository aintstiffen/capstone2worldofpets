#!/bin/bash
set -e

# Display PHP configuration info
php -i | grep "extension_dir"
php -i | grep "Loaded Configuration File"

# Install system dependencies
apt-get update
apt-get install -y libicu-dev libzip-dev

# Install PHP extensions
docker-php-ext-configure intl
docker-php-ext-install intl zip

# Verify PHP extensions
php -m | grep -E 'intl|zip'

# Start Laravel application
php artisan migrate --force
php artisan optimize
php artisan serve --host=0.0.0.0 --port=$PORT