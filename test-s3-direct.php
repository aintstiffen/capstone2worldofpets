<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Get AWS credentials from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create an S3 client
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => $_ENV['AWS_DEFAULT_REGION'],
    'credentials' => [
        'key'    => $_ENV['AWS_ACCESS_KEY_ID'],
        'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
    ],
]);

try {
    // Check if the bucket exists and is accessible
    echo "Checking S3 bucket...\n";
    $bucket = $_ENV['AWS_BUCKET'];
    
    // Try to list objects
    $objects = $s3->listObjects([
        'Bucket' => $bucket,
        'MaxKeys' => 5,
    ]);
    
    echo "✓ S3 bucket '{$bucket}' exists and is accessible.\n";
    
    // Show the first few objects
    echo "Objects in bucket:\n";
    if (isset($objects['Contents']) && count($objects['Contents']) > 0) {
        foreach ($objects['Contents'] as $object) {
            echo "- " . $object['Key'] . " (size: " . $object['Size'] . " bytes)\n";
        }
    } else {
        echo "The bucket is empty.\n";
    }
    
    // Try uploading a test file
    $testKey = 'test-file-' . time() . '.txt';
    echo "Uploading test file as '{$testKey}'...\n";
    
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $testKey,
        'Body'   => 'This is a test file created on ' . date('Y-m-d H:i:s'),
        // Removed ACL parameter - bucket has ACLs disabled
    ]);
    
    echo "✓ File uploaded successfully.\n";
    echo "URL: " . $result['ObjectURL'] . "\n";
    
} catch (AwsException $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "AWS Error Code: " . $e->getAwsErrorCode() . "\n";
    echo "AWS Error Type: " . $e->getAwsErrorType() . "\n";
    echo "AWS Request ID: " . $e->getAwsRequestId() . "\n";
}