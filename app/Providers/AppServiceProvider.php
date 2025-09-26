<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        
        // Configure Livewire file uploads to use proper authentication
        $this->configureLivewireUploads();
        
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('b2');

        // Only register if the adapter supports the builder method
        if (method_exists($disk, 'buildTemporaryUrlsUsing')) {
            $disk->buildTemporaryUrlsUsing(function (string $path, $expiration, array $options = []) use ($disk) {
                // Use the same disk instance to generate the temporary URL
                return $disk->temporaryUrl($path, $expiration, $options);
            });
        }
    }

    /**
     * Configure Livewire file uploads to work with B2 storage.
     */
    private function configureLivewireUploads(): void
    {
        // For B2 storage, we need local temp storage for Livewire uploads
        // The final files will be moved to B2 by Filament after processing
        $tempDisk = Storage::disk('public'); // Always use local public disk for temp
        $directory = config('livewire.temporary_file_upload.directory');
        
        if (!$tempDisk->exists($directory)) {
            try {
                $tempDisk->makeDirectory($directory);
                
                // Add security files to prevent direct access
                $tempDisk->put($directory . '/.htaccess', 
                    "Order Deny,Allow\nDeny from all\nAllow from 127.0.0.1"
                );
                
                // Add index.php to prevent directory listing
                $tempDisk->put($directory . '/index.php', '<?php // Silence is golden');
                
            } catch (\Exception $e) {
                \Log::error('Failed to create Livewire upload directory: ' . $e->getMessage());
            }
        }
        
        // Ensure B2 disk is properly configured
        try {
            $b2Disk = Storage::disk('b2');
            // Test B2 connection (optional - comment out in production if causing issues)
            // $b2Disk->exists('test-connection');
        } catch (\Exception $e) {
            \Log::error('B2 storage configuration error: ' . $e->getMessage());
        }
    }
}
