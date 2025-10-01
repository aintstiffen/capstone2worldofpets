@echo off
echo === Fixing S3 Upload Issues ===
echo.
echo 1. Clearing Laravel cache files...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo 2. Running the debug upload test...
php debug-upload.php

echo.
echo 3. All fixes applied and cache cleared
echo.
echo Please try uploading images in Filament admin panel now.
echo.
pause