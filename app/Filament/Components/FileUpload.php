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
                // If DB already stores a full URL, use it directly for preview
                if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
                    $basename = basename(parse_url($file, PHP_URL_PATH) ?? 'file');
                    $extension = pathinfo($basename, PATHINFO_EXTENSION);
                    $mimeType = match (strtolower($extension)) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp',
                        'pdf' => 'application/pdf',
                        default => 'application/octet-stream',
                    };

                    return [
                        'name' => $basename,
                        'size' => null,
                        'type' => $mimeType,
                        'url' => $file,
                    ];
                }

                $disk = $this->getDiskName();
                $storage = \Illuminate\Support\Facades\Storage::disk($disk);

                if (! $storage->exists($file)) {
                    \Log::warning('File does not exist on disk: ' . $file);
                    return null;
                }

                // Generate URL for the file
                if ($disk === 's3') {
                    $s3Url = config('filesystems.disks.s3.url');
                    $bucket = config('filesystems.disks.s3.bucket');
                    $region = config('filesystems.disks.s3.region');
                    $url = $s3Url
                        ? rtrim($s3Url, '/') . '/' . ltrim($file, '/')
                        : "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($file, '/');
                } else {
                    $url = '/storage/' . ltrim($file, '/');
                }

                $fileName = ($this->isMultiple() ? ($storedFileNames[$file] ?? null) : $storedFileNames) ?? basename($file);

                // Determine mime from extension
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
                    'size' => $storage->size($file),
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