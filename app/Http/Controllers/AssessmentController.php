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
    public function index(Request $request)
    {
        // Check if an assessment ID is specified and the user is logged in
        if ($request->has('id') && auth()->check()) {
            $assessmentId = $request->input('id');
            $assessment = Assessment::where('id', $assessmentId)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($assessment) {
                // Load the assessment from the database into the session
                Session::put('assessment_results', [
                    'petType' => $assessment->pet_type,
                    'preferences' => $assessment->preferences,
                    'recommendedBreeds' => $assessment->results,
                    'personality_scores' => $assessment->personality_scores ?? [],
                ]);
                
                Log::info('Loaded assessment #' . $assessmentId . ' from database for user #' . auth()->id());
            }
        }
        
        // Check if reset parameter is present and clear session data
        if ($request->has('reset')) {
            Log::debug('Reset parameter detected, clearing assessment results');
            Session::forget('assessment_results');
            // For debugging only
            if ($request->has('debug')) {
                dd('Session reset complete, assessment_results removed from session');
            }
        }
        
        // Get dog and cat breeds from database
    $dogBreeds = Pet::where('category', 'dog')->get()->map(function($pet) {
            $sizeMap = [
                'Small' => 'small',
                'Small to Medium' => 'small',
                'Medium' => 'medium',
                'Medium to Large' => 'medium',
                'Large' => 'large',
            ];
            
            // Determine hair length from description or default to 'short'
            $hairLength = 'short';
            if (stripos($pet->description, 'long hair') !== false || 
                stripos($pet->description, 'long coat') !== false || 
                stripos($pet->description, 'luxurious coat') !== false) {
                $hairLength = 'long';
            }
            
            // Extract size from the size field
            $mappedSize = $sizeMap[$pet->size] ?? 'medium';
            
            // Extract traits from temperament
            $traits = explode(', ', $pet->temperament);
            $traits = array_slice($traits, 0, 3); // Limit to 3 traits
            
            // Define personality matches based on temperament
            $personalityMatch = [];
            $temperamentLower = strtolower($pet->temperament);
            
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
            $personalityMatch = array_slice($personalityMatch, 0, 2);
            
            return [
                'id' => $pet->id,
                'slug' => $pet->slug,
                'name' => $pet->name,
                'size' => $mappedSize,
                'hairLength' => $hairLength,
                // Use the same accessor and fallback placeholder as breed pages
                'image' => $pet->image_url ?: '/placeholder.svg?height=300&width=400',
                'description' => $pet->description,
                'traits' => $traits,
                'personalityMatch' => $personalityMatch
            ];
        })->toArray();
        
        $catBreeds = Pet::where('category', 'cat')->get()->map(function($pet) {
            $sizeMap = [
                'Small' => 'small',
                'Small to Medium' => 'small', 
                'Medium' => 'medium',
                'Medium to Large' => 'medium',
                'Large' => 'large',
            ];
            
            // Determine hair length from description or default to 'short'
            $hairLength = 'short';
            if (stripos($pet->description, 'long hair') !== false || 
                stripos($pet->description, 'long coat') !== false || 
                stripos($pet->description, 'luxurious coat') !== false) {
                $hairLength = 'long';
            }
            
            // Extract size from the size field
            $mappedSize = $sizeMap[$pet->size] ?? 'medium';
            
            // Extract traits from temperament
            $traits = explode(', ', $pet->temperament);
            $traits = array_slice($traits, 0, 3); // Limit to 3 traits
            
            // Define personality matches based on temperament
            $personalityMatch = [];
            $temperamentLower = strtolower($pet->temperament);
            
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
                $personalityMatch[] = 'highAgreeableness';
            }
            
            // Get just the first 2 personality matches
            $personalityMatch = array_slice($personalityMatch, 0, 2);
            
            return [
                'id' => $pet->id,
                'slug' => $pet->slug,
                'name' => $pet->name,
                'size' => $mappedSize,
                'hairLength' => $hairLength,
                // Use the same accessor and fallback placeholder as breed pages
                'image' => $pet->image_url ?: '/placeholder.svg?height=300&width=400',
                'description' => $pet->description,
                'traits' => $traits,
                'personalityMatch' => $personalityMatch
            ];
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
                
                // Save to session so it's available for the rest of the request
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
        
        // Check if we have saved results and normalize them with complete, current DB data
        if (($savedResults['petType'] ?? null) && !empty($savedResults['recommendedBreeds'])) {
            foreach ($savedResults['recommendedBreeds'] as $index => $breed) {
                // Try to resolve the Pet by id first, then by slug
                $petInfo = null;
                if (!empty($breed['id'])) {
                    $petInfo = Pet::find($breed['id']);
                }
                // If ID lookup fails or points to a different slug, prefer slug to ensure correctness
                if (!empty($breed['slug'])) {
                    if (!$petInfo || ($petInfo && $petInfo->slug !== $breed['slug'])) {
                        $bySlug = Pet::where('slug', $breed['slug'])->first();
                        if ($bySlug) {
                            $petInfo = $bySlug;
                        }
                    }
                }

                if ($petInfo) {
                    // Always refresh with canonical data to avoid stale/placeholder images
                    $savedResults['recommendedBreeds'][$index] = [
                        'id' => $petInfo->id,
                        'slug' => $petInfo->slug,
                        'name' => $petInfo->name,
                        // Preferences-driven summary fields (for display chips on the card)
                        'size' => $savedResults['preferences']['size'] ?? 'medium',
                        'hairLength' => $savedResults['preferences']['hairLength'] ?? 'short',
                        // Use the same accessor as breed pages so images always match
                        'image' => $petInfo->image_url ?: '/placeholder.svg?height=300&width=400',
                        'description' => $petInfo->description,
                        'traits' => array_slice(explode(', ', (string) $petInfo->temperament), 0, 3),
                    ];
                }
            }

            // Re-save the normalized results to session so the view uses updated images
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
                // Ensure each breed has all required fields
                if (!isset($breed['traits']) || !is_array($breed['traits'])) {
                    $breed['traits'] = [];
                }
                
                // Make sure image is set
                if (empty($breed['image'])) {
                    // Try to pull from DB; if not, use the neutral placeholder used by breed pages
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
        
        // Encode with JSON_HEX_APOS and JSON_HEX_QUOT to avoid issues with single quotes in JavaScript
        return view('assessment', [
            'dogBreeds' => json_encode($dogBreeds, JSON_HEX_APOS | JSON_HEX_QUOT),
            'catBreeds' => json_encode($catBreeds, JSON_HEX_APOS | JSON_HEX_QUOT),
            'savedResults' => json_encode($savedResults, JSON_HEX_APOS | JSON_HEX_QUOT)
        ]);
    }
    
    public function saveResults(SaveAssessmentRequest $request)
    {
        // Handle reset case - clear session and return success
        if ($request->input('petType') === null) {
            Log::debug('Clearing assessment results from session');
            Session::forget('assessment_results');
            return response()->json(['success' => true, 'message' => 'Assessment data cleared']);
        }
        
        // The validation is handled by SaveAssessmentRequest class
        $results = $request->validated();
        
        Log::debug('Saving assessment results', [
            'petType' => $results['petType'],
            'breedCount' => count($results['recommendedBreeds'])
        ]);
        
        // Get the full breed info for each recommended breed
        $petType = $results['petType'];
        $recommendedBreeds = $results['recommendedBreeds'];
        
        // Retrieve the complete information for each breed
        $fullBreedInfo = [];
        foreach ($recommendedBreeds as $breed) {
            $breedId = $breed['id'];
            
            // Find the complete breed info from database
            $petInfo = Pet::find($breedId);
            if ($petInfo) {
                // Format pet data the same way we do in index method
                $sizeMap = [
                    'Small' => 'small',
                    'Small to Medium' => 'small',
                    'Medium' => 'medium',
                    'Medium to Large' => 'medium',
                    'Large' => 'large',
                ];
                
                // Determine hair length from description or default to 'short'
                $hairLength = 'short';
                if (stripos($petInfo->description, 'long hair') !== false || 
                    stripos($petInfo->description, 'long coat') !== false || 
                    stripos($petInfo->description, 'luxurious coat') !== false) {
                    $hairLength = 'long';
                }
                
                // Extract traits from temperament
                $traits = explode(', ', $petInfo->temperament);
                $traits = array_slice($traits, 0, 3); // Limit to 3 traits
                
                // Store complete breed info
                $fullBreedInfo[] = [
                    'id' => $petInfo->id,
                    'slug' => $petInfo->slug,
                    'name' => $petInfo->name,
                    'size' => $sizeMap[$petInfo->size] ?? 'medium',
                    'hairLength' => $hairLength,
                    // Ensure assessment uses the same image as breed pages
                    'image' => $petInfo->image_url ?: (
                        $petType === 'dog' 
                            ? ("https://placedog.net/400/300?id=" . $petInfo->id) 
                            : ("https://placekitten.com/400/300?image=" . $petInfo->id)
                    ),
                    'description' => $petInfo->description,
                    'traits' => $traits,
                ];
            }
        }
        
        // Update the results with complete breed info
        $results['recommendedBreeds'] = $fullBreedInfo;
        
        // Log what we're about to save to help with debugging
        Log::debug('Final assessment results being saved to session', [
            'petType' => $results['petType'],
            'breedCount' => count($fullBreedInfo),
            'hasSizePreference' => isset($results['preferences']['size']),
            'hasHairLengthPreference' => isset($results['preferences']['hairLength'])
        ]);
        
        // Make sure we have valid breed data before saving
        if (count($fullBreedInfo) === 0) {
            Log::warning('No valid breed information found to save');
        }
        
        // Save to session
        Session::put('assessment_results', $results);
        
        // Add flash message that will be available on the next request
        Session::flash('assessment_saved', true);
        
        // Also save to database for statistics
        try {
            // Get personality scores from request if available
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
            // Don't fail the request, as we've already saved to session
        }
        
        return response()->json([
            'success' => true, 
            'savedBreeds' => count($fullBreedInfo),
            'petType' => $results['petType']
        ]);
    }
}
