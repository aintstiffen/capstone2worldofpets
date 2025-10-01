<?php

// This script tests file upload to S3 using our custom providers
// Save this to test-s3-upload.php and run it with "php test-s3-upload.php"

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Run the application
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->bootstrap();

// Verify environment variables
echo "Environment Configuration:\n";
echo "------------------------\n";
echo "FILESYSTEM_DISK: " . env('FILESYSTEM_DISK') . "\n";
echo "AWS_ACCESS_KEY_ID: " . (env('AWS_ACCESS_KEY_ID') ? '[Set]' : '[Not Set]') . "\n";
echo "AWS_SECRET_ACCESS_KEY: " . (env('AWS_SECRET_ACCESS_KEY') ? '[Set]' : '[Not Set]') . "\n";
echo "AWS_DEFAULT_REGION: " . env('AWS_DEFAULT_REGION') . "\n";
echo "AWS_BUCKET: " . env('AWS_BUCKET') . "\n";
echo "AWS_URL: " . env('AWS_URL') . "\n\n";

// Test S3 connectivity
echo "Testing S3 Connectivity:\n";
echo "------------------------\n";
try {
    $disk = \Illuminate\Support\Facades\Storage::disk('s3');
    $testFile = 'test-upload-' . time() . '.txt';
    $content = 'This is a test file created at ' . date('Y-m-d H:i:s');
    
    // Create a test file
    $disk->put($testFile, $content);
    
    echo "File uploaded successfully: $testFile\n";
    
    // Check if the file exists
    if ($disk->exists($testFile)) {
        echo "File exists check: PASSED\n";
    } else {
        echo "File exists check: FAILED\n";
    }
    
    // Get URL of the file
    $url = $disk->url($testFile);
    echo "Generated URL: $url\n";
    
    // Read the file
    $content = $disk->get($testFile);
    echo "File content retrieved: " . strlen($content) . " characters\n";
    
    // Clean up - delete the file
    $disk->delete($testFile);
    echo "File deleted.\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest complete.\n";