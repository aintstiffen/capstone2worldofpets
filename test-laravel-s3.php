<?php

// Bootstrap Laravel environment to use the proper S3 configuration
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Now we can use Laravel's Storage facade with our customized S3 provider
use Illuminate\Support\Facades\Storage;

echo "Testing S3 Connection with Laravel Storage\n";
echo "=========================================\n\n";

try {
    // Check configuration 
    echo "S3 Configuration:\n";
    echo "- Default Disk: " . config('filesystems.default') . "\n";
    echo "- S3 Bucket: " . config('filesystems.disks.s3.bucket') . "\n";
    echo "- S3 Region: " . config('filesystems.disks.s3.region') . "\n";
    echo "- S3 URL: " . config('filesystems.disks.s3.url') . "\n\n";
    
    // Test file operations
    $testFile = 'test-laravel-' . time() . '.txt';
    $testContent = 'This is a test file created at ' . now() . ' using Laravel Storage.';
    
    echo "Testing file upload...\n";
    
    // Attempt to write file
    $success = Storage::disk('s3')->put($testFile, $testContent);
    
    if ($success) {
        echo "✓ File uploaded successfully: {$testFile}\n";
        
        // Check if file exists
        if (Storage::disk('s3')->exists($testFile)) {
            echo "✓ File exists on S3\n";
            
            // Generate URL
            $url = config('filesystems.disks.s3.url') . '/' . $testFile;
            echo "File URL: {$url}\n";
            
            // Try to read content back
            $content = Storage::disk('s3')->get($testFile);
            echo "File content: " . substr($content, 0, 30) . "...\n";
            
            // Delete the file
            Storage::disk('s3')->delete($testFile);
            echo "✓ Test file deleted\n";
        } else {
            echo "✗ Error: File doesn't exist after upload\n";
        }
    } else {
        echo "✗ Error: Failed to upload file\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if (method_exists($e, 'getResponse')) {
        echo "  Response: " . $e->getResponse() . "\n";
    }
}