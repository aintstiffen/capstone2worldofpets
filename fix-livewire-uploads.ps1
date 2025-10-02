# Fix Livewire Upload Issues Script for Windows PowerShell

Write-Host "Fixing Livewire file upload configuration..." -ForegroundColor Green

# Navigate to project directory
Set-Location "c:\Users\stiffen\Herd\sites\petsofworld"

# Clear all Laravel caches
Write-Host "Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

# Create storage link if it doesn't exist
Write-Host "Creating storage link..." -ForegroundColor Yellow
php artisan storage:link

# Create Livewire temp directory (matches 'local' disk => storage/app/private)
Write-Host "Creating Livewire temp directory..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "storage\app\private\livewire-tmp" -Force

Write-Host "Livewire upload configuration has been fixed!" -ForegroundColor Green
Write-Host "Please try uploading a file now." -ForegroundColor Cyan