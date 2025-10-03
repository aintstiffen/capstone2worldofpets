<?php
// Remove or comment out any S3 related code in this file
// If you're using R2 only, you might want to rename this provider to R2ServiceProvider

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class S3ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register R2 storage driver (using S3 compatibility)
        Storage::extend('r2', function ($app, $config) {
            $client = new S3Client([
                'credentials' => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region'  => $config['region'],
                'version' => 'latest',
                'endpoint' => $config['endpoint'],
                'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            ]);

            $adapter = new AwsS3V3Adapter($client, $config['bucket']);
            
            return new Filesystem($adapter, ['visibility' => 'public']);
        });
    }
}