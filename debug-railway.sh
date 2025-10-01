#!/bin/bash

echo "=== Railway Deployment Debug ==="
echo "PHP Version: $(php -v | head -n 1)"
echo "Environment: $APP_ENV"
echo "Current Directory: $(pwd)"
echo "Files in current directory:"
ls -la

echo "=== Testing PHP ==="
php -v

echo "=== Testing Composer ==="
composer --version

echo "=== Testing Laravel ==="
php artisan --version

echo "=== Environment Variables ==="
echo "APP_ENV: $APP_ENV"
echo "DATABASE_HOST: $DATABASE_HOST"
echo "DATABASE_PORT: $DATABASE_PORT" 
echo "DATABASE_NAME: $DATABASE_NAME"
echo "PORT: $PORT"

echo "=== Testing Database Connection ==="
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

echo "=== Running Migrations ==="
php artisan migrate --force

echo "=== Starting Application ==="
echo "Application should start on port $PORT"