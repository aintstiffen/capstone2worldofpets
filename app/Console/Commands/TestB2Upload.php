<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestB2Upload extends Command
{
    protected $signature = 'test:b2-upload';
    protected $description = 'Test B2 storage upload functionality';

    public function handle()
    {
        $this->info('Testing B2 storage configuration...');
        
        try {
            $b2Disk = Storage::disk('b2');
            $publicDisk = Storage::disk('public');
            
            // Test 1: Basic B2 connectivity
            $this->info('1. Testing basic B2 connectivity...');
            $testContent = 'Test file content - ' . now();
            $testPath = 'test/connectivity-test-' . time() . '.txt';
            
            $writeSuccess = $b2Disk->put($testPath, $testContent);
            if ($writeSuccess) {
                $this->info('✓ Successfully wrote to B2');
                
                $readContent = $b2Disk->get($testPath);
                if ($readContent === $testContent) {
                    $this->info('✓ Successfully read from B2');
                    $b2Disk->delete($testPath);
                    $this->info('✓ Successfully deleted from B2');
                } else {
                    $this->error('✗ Content mismatch when reading from B2');
                    return 1;
                }
            } else {
                $this->error('✗ Failed to write to B2 storage');
                return 1;
            }
            
            // Test 2: Public disk temporary directory
            $this->info('2. Testing public disk temporary directory...');
            $tempDir = 'livewire-tmp';
            if (!$publicDisk->exists($tempDir)) {
                $publicDisk->makeDirectory($tempDir);
                $this->info('✓ Created livewire-tmp directory');
            } else {
                $this->info('✓ livewire-tmp directory exists');
            }
            
            // Test 3: Simulate file transfer from public to B2
            $this->info('3. Testing file transfer simulation...');
            $tempFile = $tempDir . '/test-image-' . time() . '.jpg';
            $testImageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'); // 1x1 transparent GIF
            
            $publicDisk->put($tempFile, $testImageContent);
            $this->info('✓ Created temporary file on public disk: ' . $tempFile);
            
            // Transfer to B2
            $b2Path = 'image/test-' . time() . '.jpg';
            $fileContent = $publicDisk->get($tempFile);
            
            if ($fileContent !== false) {
                $uploadSuccess = $b2Disk->put($b2Path, $fileContent, [
                    'visibility' => 'private',
                ]);
                
                if ($uploadSuccess) {
                    $this->info('✓ Successfully transferred file to B2: ' . $b2Path);
                    
                    // Verify the file exists on B2
                    if ($b2Disk->exists($b2Path)) {
                        $this->info('✓ File verified to exist on B2');
                        
                        // Clean up
                        $b2Disk->delete($b2Path);
                        $publicDisk->delete($tempFile);
                        $this->info('✓ Cleanup completed');
                    } else {
                        $this->error('✗ File was not found on B2 after upload');
                        return 1;
                    }
                } else {
                    $this->error('✗ Failed to transfer file to B2');
                    return 1;
                }
            } else {
                $this->error('✗ Failed to read temporary file content');
                return 1;
            }
            
            $this->info('All tests passed! B2 storage integration is working correctly.');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Exception occurred: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}