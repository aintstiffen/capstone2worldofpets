<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestS3Connection extends Command
{
    protected $signature = 'test:s3';
    protected $description = 'Test S3 connection and file upload/download';

    public function handle()
    {
        $this->info('Testing S3 connection...');
        
        // Get configuration
        $this->info('AWS Region: ' . config('filesystems.disks.s3.region'));
        $this->info('AWS Bucket: ' . config('filesystems.disks.s3.bucket'));
        $this->info('Default Filesystem: ' . config('filesystems.default'));
        
        try {
            // Test directory creation
            $testDir = 'test-' . time();
            $this->info("Creating directory: {$testDir}");
            Storage::disk('s3')->makeDirectory($testDir);
            
            // Test file upload
            $testFile = "{$testDir}/test.txt";
            $testContent = 'This is a test file created at ' . now()->format('Y-m-d H:i:s');
            
            $this->info("Uploading file: {$testFile}");
            $success = Storage::disk('s3')->put($testFile, $testContent);
            
            if ($success) {
                $this->info("✅ File uploaded successfully");
                
                // Try to get the file back
                if (Storage::disk('s3')->exists($testFile)) {
                    $this->info("✅ File exists on S3");
                    
                    $content = Storage::disk('s3')->get($testFile);
                    $this->info("Content: {$content}");
                    
                    // Get public URL if possible
                    try {
                        // For public files
                        if (method_exists(Storage::disk('s3'), 'url')) {
                            $url = Storage::disk('s3')->url($testFile);
                            $this->info("Public URL: {$url}");
                        }
                    } catch (\Exception $e) {
                        $this->warn("Could not generate URL: " . $e->getMessage());
                    }
                } else {
                    $this->error("❌ File was not found after upload");
                }
            } else {
                $this->error("❌ Failed to upload file");
            }
            
            // List files in the test directory
            $files = Storage::disk('s3')->files($testDir);
            $this->info("Files in {$testDir}:");
            foreach ($files as $file) {
                $this->info("- {$file}");
            }
            
            $this->info('S3 test completed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            return 1;
        }
        
        return 0;
    }
}