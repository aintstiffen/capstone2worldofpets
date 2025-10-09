<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Assessment;
use App\Http\Requests\SaveAssessmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    /**
     * Determine hair length for a pet based on breed name and description
     */
    private function determineHairLength($pet)
    {
        $nameLower = strtolower($pet->name);
        $descLower = strtolower($pet->description ?? '');
        
        // Known long-haired breeds (this is more reliable than description parsing)
        $longHairBreeds = [
            'afghan hound', 'shih tzu', 'maltese', 'yorkshire terrier', 'lhasa apso',
            'pomeranian', 'pekingese', 'havanese', 'cocker spaniel', 'golden retriever',
            'rough collie', 'shetland sheepdog', 'old english sheepdog', 'bearded collie',
            'tibetan terrier', 'portuguese water dog', 'bernese mountain dog',
            'newfoundland', 'samoyed', 'chow chow', 'keeshond', 'finnish lapphund',
            'persian', 'maine coon', 'ragdoll', 'himalayan', 'norwegian forest',
            'siberian', 'birman', 'turkish angora', 'ragamuffin', 'somali'
        ];
        
        // Check if breed name matches known long-haired breeds
        foreach ($longHairBreeds as $breed) {
            if (stripos($nameLower, $breed) !== false) {
                return 'long';
            }
        }
        
        // Check description for long hair indicators
        if (stripos($descLower, 'long hair') !== false || 
            stripos($descLower, 'long coat') !== false || 
            stripos($descLower, 'luxurious coat') !== false ||
            stripos($descLower, 'flowing coat') !== false ||
            stripos($descLower, 'silky coat') !== false) {
            return 'long';
        }
        
        // Default to short
        return 'short';
    }
    
    /**
     * Extract personality matches from temperament
     */
    private function extractPersonalityMatches($temperament)
    {
        $personalityMatch = [];
        $temperamentLower = strtolower($temperament);
        
        if (stripos($temperamentLower, 'friendly') !== false || 
            stripos($temperamentLower, 'outgoing') !== false || 
            stripos($temperamentLower, 'social') !== false) {
            $personalityMatch[] = 'highExtraversion';
        }
        
        if (stripos($temperamentLower, 'gentle') !== false || 
            stripos($temperamentLower, 'affectionate') !== false || 
            stripos($temperamentLower, 'loving') !== false) {
            $personalityMatch[] = 'highAgreeableness';
        }
        
        if (stripos($temperamentLower, 'intelligent') !== false || 
            stripos($temperamentLower, 'trainable') !== false || 
            stripos($temperamentLower, 'smart') !== false) {
            $personalityMatch[] = 'highConscientiousness';
        }
        
        if (stripos($temperamentLower, 'loyal') !== false || 
            stripos($temperamentLower, 'devoted') !== false || 
            stripos($temperamentLower, 'faithful') !== false) {
            $personalityMatch[] = 'highHonesty';
        }
        
        if (stripos($temperamentLower, 'curious') !== false || 
            stripos($temperamentLower, 'adaptable') !== false || 
            stripos($temperamentLower, 'alert') !== false) {
            $personalityMatch[] = 'highOpenness';
        }
        
        if (stripos($temperamentLower, 'sensitive') !== false || 
            stripos($temperamentLower, 'shy') !== false ||
            stripos($temperamentLower, 'loving') !== false) {
            $personalityMatch[] = 'highEmotionality';
        }
        
        // Make sure we have at least 2 personality matches
        if (count($personalityMatch) < 2) {
            $personalityMatch[] = 'highExtraversion';
        }
        
        // Get just the first 2 personality matches
        return array_slice($personalityMatch, 0, 2);
    }
    
    /**
     * Format pet data consistently
     */
    private function formatPetData($pet, $defaultPersonality = 'highExtraversion')
    {
        $sizeMap = [
            'Small' => 'small',
            'Small to Medium' => 'small',
            'Medium' => 'medium',
            'Medium to Large' => 'medium',
            'Large' => 'large',
        ];
        
        $mappedSize = $sizeMap[$pet->size] ?? 'medium';
        $hairLength = $this->determineHairLength($pet);
        $traits = array_slice(explode(', ', $pet->temperament), 0, 3);
        $personalityMatch = $this->extractPersonalityMatches($pet->temperament);
        
        return [
            'id' => $pet->id,
            'slug' => $pet->slug,
            'name' => $pet->name,
            'size' => $mappedSize,
            'hairLength' => $hairLength,
            'image' => $pet->image_url ?: '/placeholder.svg?height=300&width=400',
            'description' => $pet->description,
            'traits' => $traits,
            'personalityMatch' => $personalityMatch
        ];
    }

    public function index(Request $request, $id = null)
    {
        // Check if an assessment ID is specified
        $assessmentId = $id ?: ($request->has('id') ? $request->input('id') : null);
        if ($assessmentId && auth()->check()) {
            $assessment = Assessment::where('id', $assessmentId)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($assessment) {
                Session::put('assessment_results', [
                    'petType' => $assessment->pet_type,
                    'preferences' => $assessment->preferences,
                    'recommendedBreeds' => $assessment->results,
                    'personality_scores' => $assessment->personality_scores ?? [],
                ]);
                
                Log::info('Loaded assessment #' . $assessmentId . ' from database for user #' . auth()->id());
            }
        }
        
        // Check if reset parameter is present
        if ($request->has('reset')) {
            Log::debug('Reset parameter detected, clearing assessment results');
            Session::forget('assessment_results');
            if ($request->has('debug')) {
                dd('Session reset complete, assessment_results removed from session');
            }
        }
        
        // Get dog and cat breeds from database
        $dogBreeds = Pet::where('category', 'dog')->get()->map(function($pet) {
            return $this->formatPetData($pet, 'highExtraversion');
        })->toArray();
        
        $catBreeds = Pet::where('category', 'cat')->get()->map(function($pet) {
            return $this->formatPetData($pet, 'highAgreeableness');
        })->toArray();

        // Get any saved assessment results from the session
        $savedResults = Session::get('assessment_results');
        
        // If no saved results in session but user is logged in, check for their most recent assessment
        if ((!$savedResults || empty($savedResults)) && auth()->check()) {
            $latestAssessment = Assessment::where('user_id', auth()->id())
                ->latest()
                ->first();
                
            if ($latestAssessment) {
                $savedResults = [
                    'petType' => $latestAssessment->pet_type,
                    'preferences' => $latestAssessment->preferences,
                    'recommendedBreeds' => $latestAssessment->results,
                    'personality_scores' => $latestAssessment->personality_scores ?? [],
                ];
                
                Session::put('assessment_results', $savedResults);
                Log::info('Loaded latest assessment from database for user #' . auth()->id());
            }
        }
        
        // If we still don't have saved results, initialize with empty values
        if (!$savedResults) {
            $savedResults = [
                'petType' => null,
                'preferences' => null,
                'recommendedBreeds' => []
            ];
        }
        
        // CRITICAL FIX: Normalize saved results with ACTUAL breed characteristics
        if (($savedResults['petType'] ?? null) && !empty($savedResults['recommendedBreeds'])) {
            foreach ($savedResults['recommendedBreeds'] as $index => $breed) {
                // Try to resolve the Pet by id first, then by slug
                $petInfo = null;
                if (!empty($breed['id'])) {
                    $petInfo = Pet::find($breed['id']);
                }
                if (!empty($breed['slug'])) {
                    if (!$petInfo || ($petInfo && $petInfo->slug !== $breed['slug'])) {
                        $bySlug = Pet::where('slug', $breed['slug'])->first();
                        if ($bySlug) {
                            $petInfo = $bySlug;
                        }
                    }
                }

                if ($petInfo) {
                    // Use formatPetData to get ACTUAL characteristics, not preferences
                    $savedResults['recommendedBreeds'][$index] = $this->formatPetData($petInfo);
                }
            }

            // Re-save the normalized results to session
            Session::put('assessment_results', $savedResults);
        }

        // Debug the saved results before passing to the view
        Log::debug('Passing saved results to view:', [
            'petType' => $savedResults['petType'] ?? null,
            'hasPreferences' => !empty($savedResults['preferences']),
            'breedCount' => count($savedResults['recommendedBreeds'] ?? [])
        ]);
        
        // Ensure all required fields are present in savedResults
        if (!empty($savedResults['recommendedBreeds'])) {
            foreach ($savedResults['recommendedBreeds'] as &$breed) {
                if (!isset($breed['traits']) || !is_array($breed['traits'])) {
                    $breed['traits'] = [];
                }
                
                if (empty($breed['image'])) {
                    $pet = null;
                    if (!empty($breed['id'])) {
                        $pet = Pet::find($breed['id']);
                    }
                    if (!$pet && !empty($breed['slug'])) {
                        $pet = Pet::where('slug', $breed['slug'])->first();
                    }
                    $breed['image'] = $pet && $pet->image_url ? $pet->image_url : '/placeholder.svg?height=300&width=400';
                }
            }
        }
        
        return view('assessment', [
            'dogBreeds' => json_encode($dogBreeds, JSON_HEX_APOS | JSON_HEX_QUOT),
            'catBreeds' => json_encode($catBreeds, JSON_HEX_APOS | JSON_HEX_QUOT),
            'savedResults' => json_encode($savedResults, JSON_HEX_APOS | JSON_HEX_QUOT)
        ]);
    }
    
    public function saveResults(SaveAssessmentRequest $request)
    {
        // Handle reset case
        if ($request->input('petType') === null) {
            Log::debug('Clearing assessment results from session');
            Session::forget('assessment_results');
            return response()->json(['success' => true, 'message' => 'Assessment data cleared']);
        }
        
        $results = $request->validated();
        
        Log::debug('Saving assessment results', [
            'petType' => $results['petType'],
            'breedCount' => count($results['recommendedBreeds'])
        ]);
        
        $petType = $results['petType'];
        $recommendedBreeds = $results['recommendedBreeds'];
        
        // Retrieve the complete information for each breed
        $fullBreedInfo = [];
        foreach ($recommendedBreeds as $breed) {
            $breedId = $breed['id'];
            $petInfo = Pet::find($breedId);
            
            if ($petInfo) {
                // Use formatPetData for consistency
                $fullBreedInfo[] = $this->formatPetData($petInfo);
            }
        }
        
        // Update the results with complete breed info
        $results['recommendedBreeds'] = $fullBreedInfo;
        
        Log::debug('Final assessment results being saved to session', [
            'petType' => $results['petType'],
            'breedCount' => count($fullBreedInfo),
            'hasSizePreference' => isset($results['preferences']['size']),
            'hasHairLengthPreference' => isset($results['preferences']['hairLength'])
        ]);
        
        if (count($fullBreedInfo) === 0) {
            Log::warning('No valid breed information found to save');
        }
        
        // Save to session
        Session::put('assessment_results', $results);
        Session::flash('assessment_saved', true);
        
        // Save to database
        try {
            $personalityScores = $request->input('personalityScores', []);
            
            Assessment::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'pet_type' => $results['petType'],
                'preferences' => $results['preferences'],
                'results' => $fullBreedInfo,
                'personality_scores' => $personalityScores,
            ]);
            
            Log::info('Assessment saved to database');
        } catch (\Exception $e) {
            Log::error('Failed to save assessment to database: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true, 
            'savedBreeds' => count($fullBreedInfo),
            'petType' => $results['petType']
        ]);
    }
}