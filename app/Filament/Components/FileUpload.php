<?php

namespace App\Filament\Components;

use Filament\Forms\Components\FileUpload as BaseFileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUpload extends BaseFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        // Override the file saving process to avoid using visibility settings
        $this->saveUploadedFileUsing(function (TemporaryUploadedFile $file, callable $set): ?string {
            try {
                $directory = $this->getDirectory();
                $diskName = $this->getDiskName();
                
                // Use our custom method that doesn't set ACLs
                $filename = $this->getUploadedFileNameForStorage($file);
                
                // Store file without ACL settings
                if (method_exists($file, 'storePubliclyAsWithoutAcl')) {
                    $path = $file->storePubliclyAsWithoutAcl($directory, $filename, $diskName);
                } else {
                    $path = $file->storeAs($directory, $filename, $diskName);
                }
                
                // Log successful upload
                \Log::info('Custom FileUpload: Successfully uploaded file', [
                    'path' => $path,
                    'disk' => $diskName,
                    'directory' => $directory
                ]);
                
                return $path;
            } catch (\Exception $e) {
                // Log any errors
                \Log::error('Custom FileUpload: Error uploading file', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
        
        // Override the URL generation method
        $this->getUploadedFileUrlsUsing(function (array $files): array {
            $urls = [];
            $disk = $this->getDiskName();
            
            foreach ($files as $file) {
                if ($disk === 's3') {
                    // Get S3 URL from environment or config
                    $s3Url = config('filesystems.disks.s3.url');
                    $bucket = config('filesystems.disks.s3.bucket');
                    $region = config('filesystems.disks.s3.region');
                    
                    if ($s3Url) {
                        $urls[$file] = rtrim($s3Url, '/') . '/' . ltrim($file, '/');
                    } else {
                        $urls[$file] = "https://{$bucket}.s3.{$region}.amazonaws.com/{$file}";
                    }
                } else {
                    // For other disks, just return the path as fallback
                    $urls[$file] = $file;
                }
            }
            
            return $urls;
        });
    }
}