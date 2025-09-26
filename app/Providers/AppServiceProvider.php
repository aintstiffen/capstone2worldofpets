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
}
