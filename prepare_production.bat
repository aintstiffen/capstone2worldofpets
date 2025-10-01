@echo off
REM Script to prepare and optimize Laravel application for production deployment

echo [94m👉 Starting production deployment preparation...[0m

REM Copy production environment file
echo [94m👉 Setting up production environment...[0m
copy .env.production .env
echo [92m✅ Environment file configured[0m

REM Install dependencies (if requested)
if "%1"=="--dependencies" (
  echo [94m👉 Installing composer dependencies...[0m
  composer install --no-dev --optimize-autoloader
  
  echo [94m👉 Installing npm dependencies and building assets...[0m
  npm ci
  npm run build
  echo [92m✅ Dependencies installed[0m
)

REM Optimize the application
echo [94m👉 Optimizing application for production...[0m
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo [92m✅ Application optimized[0m

REM Run migrations if requested
if "%1"=="--migrate" (
  goto :migrate
) else if "%2"=="--migrate" (
  goto :migrate
) else (
  goto :end
)

:migrate
echo [94m👉 Running database migrations...[0m
php artisan migrate --force
echo [92m✅ Migrations completed[0m

:end
echo [92m🎉 Production deployment preparation completed successfully![0m
echo [93m🔍 Don't forget to verify HTTPS configuration after deployment![0m