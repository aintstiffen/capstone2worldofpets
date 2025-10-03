<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Illuminate\Support\Facades\URL;

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
            URL::forceScheme('https');
        }
        
        // Configure Livewire uploads with no authentication middleware
        $this->configureLivewireUploads();
    }

    /**
     * Configure Livewire file uploads to bypass authentication issues.
     */
    private function configureLivewireUploads(): void
    {
        // Remove authentication middleware from Livewire uploads
        FileUploadConfiguration::middleware([
            'web',
            // Explicitly no auth middleware here
        ]);
    }
}