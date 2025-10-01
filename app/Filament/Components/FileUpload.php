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
        
        // Override the method that gets file information including URLs
        $this->getUploadedFileUsing(function (string $file, $storedFileNames): ?array {
            try {
                $disk = $this->getDiskName();
                $storage = \Illuminate\Support\Facades\Storage::disk($disk);
                
                // Check if file exists
                if (! $storage->exists($file)) {
                    \Log::warning('File does not exist on disk: ' . $file);
                    return null;
                }
                
                // Generate URL for the file
                $url = null;
                if ($disk === 's3') {
                    // Get S3 URL from environment or config
                    $s3Url = config('filesystems.disks.s3.url');
                    $bucket = config('filesystems.disks.s3.bucket');
                    $region = config('filesystems.disks.s3.region');
                    
                    if ($s3Url) {
                        $url = rtrim($s3Url, '/') . '/' . ltrim($file, '/');
                    } else {
                        $url = "https://{$bucket}.s3.{$region}.amazonaws.com/{$file}";
                    }
                } else {
                    // For other disks, use a simple path-based approach
                    $url = '/storage/' . $file;
                }
                
                // Get filename from stored filenames or use the basename
                $fileName = ($this->isMultiple() ? ($storedFileNames[$file] ?? null) : $storedFileNames) ?? basename($file);
                
                \Log::info('Custom FileUpload: Generated file info', [
                    'file' => $file,
                    'url' => $url,
                    'fileName' => $fileName
                ]);
                
                // Use extension to determine mime type instead of mimeType method
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $mimeType = match (strtolower($extension)) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp',
                    'pdf' => 'application/pdf',
                    default => 'application/octet-stream',
                };
                
                return [
                    'name' => $fileName,
                    'size' => $storage->exists($file) ? $storage->size($file) : 0,
                    'type' => $mimeType,
                    'url' => $url,
                ];
            } catch (\Exception $e) {
                \Log::error('Custom FileUpload: Error generating file info', [
                    'file' => $file,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return null;
            }
        });
    }
}