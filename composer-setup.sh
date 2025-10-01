#!/bin/bash
set -e

# Install system dependencies
apt-get update
apt-get install -y libicu-dev libzip-dev

# Install PHP extensions
docker-php-ext-configure intl
docker-php-ext-install intl zip

# Verify PHP extensions
php -m

# Install Composer dependencies
composer install --optimize-autoloader --no-dev --no-scripts --no-interaction