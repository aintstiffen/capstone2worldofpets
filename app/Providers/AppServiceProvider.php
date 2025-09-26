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
     * Configure Livewire file uploads to use proper authentication.
     */
    private function configureLivewireUploads(): void
    {
        // Ensure the temporary upload directory exists
        $disk = Storage::disk(config('livewire.temporary_file_upload.disk'));
        $directory = config('livewire.temporary_file_upload.directory');
        
        if (!$disk->exists($directory)) {
            try {
                $disk->makeDirectory($directory);
                
                // Add security file to prevent direct access
                $disk->put($directory . '/.htaccess', 
                    "Order Deny,Allow\nDeny from all\nAllow from 127.0.0.1"
                );
                
                // Add index.php to prevent directory listing
                $disk->put($directory . '/index.php', '<?php // Silence is golden');
                
            } catch (\Exception $e) {
                \Log::error('Failed to create Livewire upload directory: ' . $e->getMessage());
            }
        }
    }
}
