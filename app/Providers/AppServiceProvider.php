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
        
        // Configure S3 storage
        $this->configureS3Storage();
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
    
    /**
     * Configure S3 storage adapter with improved URL generation
     */
    private function configureS3Storage(): void
    {
        // Extend S3 driver to add a url() method if needed
        Storage::extend('s3', function ($app, $config) {
            $client = new \Aws\S3\S3Client([
                'credentials' => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'endpoint' => $config['endpoint'] ?? null,
                'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            ]);

            $adapter = new \League\Flysystem\AwsS3V3\AwsS3V3Adapter(
                $client, 
                $config['bucket'],
                $config['root'] ?? '',
                $config['options'] ?? []
            );

            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
        
        // Log S3 configuration
        \Log::info('S3 storage configured', [
            'bucket' => config('filesystems.disks.s3.bucket'),
            'region' => config('filesystems.disks.s3.region'),
            'url' => config('filesystems.disks.s3.url'),
        ]);
    }
}
