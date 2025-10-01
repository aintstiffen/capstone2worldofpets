<?php

namespace App\Http\Controllers;

use App\Services\CatApiService;
use App\Services\DogApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompareController extends Controller
{
    protected $catApiService;
    protected $dogApiService;

    public function __construct(CatApiService $catApiService, DogApiService $dogApiService)
    {
        $this->catApiService = $catApiService;
        $this->dogApiService = $dogApiService;
    }

    public function index()
    {
        return view('compare');
    }

    public function getBreeds($type)
    {
        try {
            if ($type === 'cat') {
                $breeds = $this->catApiService->getBreeds();
            } elseif ($type === 'dog') {
                $breeds = $this->dogApiService->getBreeds();
            } else {
                return response()->json(['error' => 'Invalid animal type'], 400);
            }

            // Format breeds for frontend
            $formattedBreeds = collect($breeds)->map(function ($breed) {
                return [
                    'id' => $breed['id'],
                    'name' => $breed['name']
                ];
            })->sortBy('name')->values();

            return response()->json($formattedBreeds);
        } catch (\Exception $e) {
            Log::error('Error fetching breeds: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch breeds'], 500);
        }
    }

    public function compare(Request $request)
    {
        $request->validate([
            'type' => 'required|in:cat,dog',
            'breed1' => 'required|string',
            'breed2' => 'required|string',
        ]);

        try {
            $type = $request->type;
            $breed1Id = $request->breed1;
            $breed2Id = $request->breed2;

            if ($type === 'cat') {
                $breed1Info = $this->catApiService->getBreedById($breed1Id);
                $breed2Info = $this->catApiService->getBreedById($breed2Id);
                $breed1Image = $this->catApiService->getBreedImage($breed1Id);
                $breed2Image = $this->catApiService->getBreedImage($breed2Id);
            } else {
                $breed1Info = $this->dogApiService->getBreedById($breed1Id);
                $breed2Info = $this->dogApiService->getBreedById($breed2Id);
                $breed1Image = $this->dogApiService->getBreedImage($breed1Id);
                $breed2Image = $this->dogApiService->getBreedImage($breed2Id);
            }

            if (!$breed1Info || !$breed2Info) {
                return response()->json(['error' => 'One or both breeds not found'], 404);
            }

            $comparison = [
                'breed1' => [
                    'info' => $breed1Info,
                    'image' => $breed1Image
                ],
                'breed2' => [
                    'info' => $breed2Info,
                    'image' => $breed2Image
                ]
            ];

            return response()->json($comparison);
        } catch (\Exception $e) {
            Log::error('Error comparing breeds: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to compare breeds'], 500);
        }
    }
}