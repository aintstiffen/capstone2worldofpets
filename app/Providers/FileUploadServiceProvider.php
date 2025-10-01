<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploadServiceProvider extends ServiceProvider
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
        // Override the storePubliclyAs method to avoid setting ACLs
        UploadedFile::macro('storePubliclyAsWithoutAcl', function ($path, $name = null, $disk = null) {
            /** @var UploadedFile $this */
            $name = $name ?? $this->hashName();
            
            // Use storeAs without visibility parameter
            return $this->storeAs($path, $name, $disk);
        });
        
        // This is necessary for Filament and other packages that directly use storePubliclyAs
        UploadedFile::macro('originalStorePubliclyAs', UploadedFile::class.'::storePubliclyAs');
        UploadedFile::macro('storePubliclyAs', function ($path, $name = null, $disk = null) {
            /** @var UploadedFile $this */
            return $this->storePubliclyAsWithoutAcl($path, $name, $disk);
        });
        
        // Also hook into TemporaryUploadedFile if it exists (used by Livewire file uploads)
        if (class_exists(TemporaryUploadedFile::class)) {
            TemporaryUploadedFile::macro('storeAs', function ($path, $name = null, $disk = null, $options = []) {
                /** @var TemporaryUploadedFile $this */
                $options = array_merge($options, ['visibility' => null]);
                
                $disk = Storage::disk($disk);
                $path = trim($path, '/') . '/';
                $fullPath = $path . $name;
                
                $stream = fopen($this->getRealPath(), 'r');
                $disk->put($fullPath, $stream, $options);
                
                if (is_resource($stream)) {
                    fclose($stream);
                }
                
                return $fullPath;
            });
        }
        
        \Log::info('FileUploadServiceProvider booted: storePubliclyAs methods overridden');
    }
}