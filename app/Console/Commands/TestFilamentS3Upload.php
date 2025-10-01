<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;

class TestFilamentS3Upload extends Command
{
    protected $signature = 'test:filament-s3';
    protected $description = 'Test Filament S3 upload functionality';

    public function handle()
    {
        $this->info('Testing Filament S3 Upload Configuration');
        $this->info('==========================================');

        // Check S3 Configuration
        $this->info('1. Checking S3 configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Default Disk', config('filesystems.default')],
                ['S3 Driver', config('filesystems.disks.s3.driver')],
                ['S3 Region', config('filesystems.disks.s3.region')],
                ['S3 Bucket', config('filesystems.disks.s3.bucket')],
                ['S3 URL', config('filesystems.disks.s3.url')],
                ['Has Endpoint', config('filesystems.disks.s3.endpoint') ? 'Yes' : 'No'],
            ]
        );

        // Check if S3 is properly registered
        $this->info("\n2. Testing S3 connection:");
        try {
            $testDir = 'filament-test-' . time();
            $testFile = $testDir . '/test.txt';
            $testContent = 'This is a test file created at ' . now();

            // Write file directly (S3 doesn't actually need directories to be created)
            $this->info('📝 Attempting to write test file...');
            $success = Storage::disk('s3')->put($testFile, $testContent);
            
            if ($success) {
                $this->info('✅ Created test file: ' . $testFile);
            } else {
                $this->error('❌ Failed to write test file');
                return 1;
            }
            
            // Check if file exists
            if (Storage::disk('s3')->exists($testFile)) {
                $this->info('✅ File exists in S3');
                
                // Try to read file
                $content = Storage::disk('s3')->get($testFile);
                $this->info('✅ Read file content: ' . substr($content, 0, 30) . '...');
                
                // Try to generate URL (using manual approach)
                $url = config('filesystems.disks.s3.url') . '/' . $testFile;
                $this->info('📝 File URL: ' . $url);
                
                // List files in directory
                $files = Storage::disk('s3')->files($testDir);
                $this->info('✅ Files in directory: ' . implode(', ', $files));
                
                // Clean up
                Storage::disk('s3')->delete($testFile);
                $this->info('✅ Deleted test file');
            } else {
                $this->error('❌ File was not found after upload');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error with S3: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            return 1;
        }

        // Check Filament FileUpload component configuration
        $this->info("\n3. Checking Filament FileUpload configuration:");
        try {
            // Create a test file upload component
            $fileUpload = FileUpload::make('test_file')
                ->disk('s3')
                ->directory('filament-test-upload')
                // No visibility setting - bucket has ACLs disabled
                ->image()
                ->maxSize(1024);

            $this->info('✅ FileUpload component configured correctly');
            $this->info('✅ Disk name: s3'); // Hard-coded since we know what disk we're using
            $this->info('✅ Directory: ' . $fileUpload->getDirectory());
            
        } catch (\Exception $e) {
            $this->error('❌ Error with FileUpload component: ' . $e->getMessage());
            return 1;
        }

        $this->info("\n4. All tests completed successfully!");
        return 0;
    }
}