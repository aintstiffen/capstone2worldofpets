<?php

// Script to debug Filament upload issues
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

echo "Testing UploadedFile with ACL workaround\n";
echo "=======================================\n\n";

// Check if our macro has been registered
if (method_exists(UploadedFile::class, 'storePubliclyAsWithoutAcl')) {
    echo "✅ storePubliclyAsWithoutAcl macro is registered\n";
} else {
    echo "❌ storePubliclyAsWithoutAcl macro is NOT registered\n";
}

if (UploadedFile::hasMacro('storePubliclyAs')) {
    echo "✅ storePubliclyAs has been overridden\n";
} else {
    echo "❌ storePubliclyAs has NOT been overridden\n";
}

try {
    // Create a test file
    $testFilePath = tempnam(sys_get_temp_dir(), 'upload_test');
    file_put_contents($testFilePath, 'Test file content');
    
    // Create an uploaded file
    $uploadedFile = new UploadedFile(
        $testFilePath,
        'test.txt',
        'text/plain',
        null,
        true
    );
    
    // Try to store it with our custom method
    echo "\nTesting file upload...\n";
    $path = $uploadedFile->storePubliclyAs('test-uploads', 'test-macro-'.time().'.txt', 's3');
    echo "✅ File uploaded successfully: {$path}\n";
    
    // Check if the file exists
    if (Storage::disk('s3')->exists($path)) {
        echo "✅ File exists on S3\n";
    } else {
        echo "❌ File doesn't exist after upload\n";
    }
    
    // Clean up
    @unlink($testFilePath);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}