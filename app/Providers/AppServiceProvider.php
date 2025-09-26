<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

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
        Storage::disk('b2')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            return Storage::disk('b2')->temporaryUrl($path, $expiration, $options);
        });
    }
}
