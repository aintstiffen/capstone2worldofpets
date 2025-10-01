<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter as IlluminateFilesystemAdapter;
use League\Flysystem\FilesystemOperator;
use RuntimeException;

class S3ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run if S3 is configured
        if (config('filesystems.disks.s3.driver') === 's3') {
            $this->configureS3();
        }
    }

    /**
     * Configure S3 with proper settings for ACL-disabled buckets
     */
    protected function configureS3(): void
    {
        // Log S3 configuration
        \Log::info('Configuring S3 storage', [
            'bucket' => config('filesystems.disks.s3.bucket'),
            'region' => config('filesystems.disks.s3.region'),
            'url' => config('filesystems.disks.s3.url'),
        ]);

        // Override the default S3 client configuration
        Storage::extend('s3', function ($app, $config) {
            $s3Config = [
                'version' => 'latest',
                'region' => $config['region'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ];

            // Add endpoint if specified
            if (!empty($config['endpoint'])) {
                $s3Config['endpoint'] = $config['endpoint'];
            }

            // Add path style endpoint if specified
            if (isset($config['use_path_style_endpoint'])) {
                $s3Config['use_path_style_endpoint'] = $config['use_path_style_endpoint'];
            }

            $client = new S3Client($s3Config);
            
            // Create adapter without any visibility handling for ACL-disabled buckets
            $adapter = new AwsS3V3Adapter(
                $client, 
                $config['bucket'],
                $config['root'] ?? ''
            );
            
            $driver = new Filesystem($adapter);

            // Create custom FilesystemAdapter that supports URL generation
            return new class($driver, $adapter, $config, $client) extends IlluminateFilesystemAdapter {
                protected $client;
                protected $bucket;
                protected $baseUrl;
                
                public function __construct(
                    FilesystemOperator $driver, 
                    $adapter, 
                    array $config,
                    S3Client $client
                ) {
                    parent::__construct($driver, $adapter, $config);
                    $this->client = $client;
                    $this->bucket = $config['bucket'] ?? null;
                    $this->baseUrl = $config['url'] ?? null;
                }
                
                /**
                 * Get the URL for the file at the given path.
                 *
                 * @param  string  $path
                 * @return string
                 */
                public function url($path)
                {
                    $path = ltrim($path, '/');
                    
                    // If we have a base URL configured, use that
                    if ($this->baseUrl) {
                        return rtrim($this->baseUrl, '/') . '/' . $path;
                    }
                    
                    // Fallback to the AWS S3 client URL generation
                    return $this->client->getObjectUrl($this->bucket, $path);
                }
                
                /**
                 * Get a temporary URL for the file at the given path.
                 *
                 * @param  string  $path
                 * @param  \DateTimeInterface  $expiration
                 * @param  array  $options
                 * @return string
                 */
                public function temporaryUrl($path, $expiration, array $options = [])
                {
                    $command = $this->client->getCommand('GetObject', array_merge([
                        'Bucket' => $this->bucket,
                        'Key' => $path,
                    ], $options));

                    $presignedRequest = $this->client->createPresignedRequest(
                        $command, $expiration
                    );

                    return (string) $presignedRequest->getUri();
                }
            };
        });
        
        // Log that the S3 service provider has been configured
        \Log::info('S3 service provider configured for ACL-disabled bucket with URL support');
    }
}