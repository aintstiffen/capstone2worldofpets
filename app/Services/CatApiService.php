<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CatApiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.cat_api.key');
        $this->baseUrl = config('services.cat_api.base_url');
    }

    public function getBreeds()
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get("{$this->baseUrl}/breeds");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Cat API Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Cat API Exception: ' . $e->getMessage());
            return [];
        }
    }

    public function getBreedById($breedId)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get("{$this->baseUrl}/breeds/{$breedId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Cat API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Cat API Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getBreedImage($breedId)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get("{$this->baseUrl}/images/search", [
                'breed_ids' => $breedId,
                'limit' => 1
            ]);

            if ($response->successful()) {
                $images = $response->json();
                return !empty($images) ? $images[0] : null;
            }

            Log::error('Cat API Image Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Cat API Image Exception: ' . $e->getMessage());
            return null;
        }
    }
}