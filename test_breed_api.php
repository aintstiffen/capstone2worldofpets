<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Illuminate\Support\Facades\Http;

// Test Dog API
echo "=== Testing Dog API ===\n\n";

$dogApiKey = $_ENV['DOG_API_KEY'] ?? '';
$dogApiUrl = $_ENV['DOG_API_BASE_URL'] ?? 'https://api.thedogapi.com/v1';

// Example: Fetch Labrador Retriever breed (ID: 149)
$breedId = 149; // Labrador Retriever

try {
    $response = file_get_contents(
        $dogApiUrl . '/breeds/' . $breedId,
        false,
        stream_context_create([
            'http' => [
                'header' => "x-api-key: $dogApiKey\r\n"
            ]
        ])
    );
    
    $breedData = json_decode($response, true);
    
    echo "Breed Name: " . ($breedData['name'] ?? 'N/A') . "\n";
    echo "Temperament: " . ($breedData['temperament'] ?? 'N/A') . "\n";
    echo "Life Span: " . ($breedData['life_span'] ?? 'N/A') . "\n";
    echo "Weight: " . ($breedData['weight']['imperial'] ?? 'N/A') . "\n";
    echo "Description: " . substr($breedData['description'] ?? 'N/A', 0, 100) . "...\n";
    echo "\nAvailable fields:\n";
    print_r(array_keys($breedData));
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n\n=== Testing Cat API ===\n\n";

$catApiKey = $_ENV['CAT_API_KEY'] ?? '';
$catApiUrl = $_ENV['CAT_API_BASE_URL'] ?? 'https://api.thecatapi.com/v1';

// Example: Fetch Abyssinian breed (ID: abys)
$breedId = 'abys';

try {
    $response = file_get_contents(
        $catApiUrl . '/breeds/' . $breedId,
        false,
        stream_context_create([
            'http' => [
                'header' => "x-api-key: $catApiKey\r\n"
            ]
        ])
    );
    
    $breedData = json_decode($response, true);
    
    echo "Breed Name: " . ($breedData['name'] ?? 'N/A') . "\n";
    echo "Temperament: " . ($breedData['temperament'] ?? 'N/A') . "\n";
    echo "Life Span: " . ($breedData['life_span'] ?? 'N/A') . "\n";
    echo "Weight: " . ($breedData['weight']['imperial'] ?? 'N/A') . "\n";
    echo "Description: " . substr($breedData['description'] ?? 'N/A', 0, 100) . "...\n";
    echo "\nAvailable fields:\n";
    print_r(array_keys($breedData));
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
