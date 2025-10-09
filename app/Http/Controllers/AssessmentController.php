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
     * Comprehensive hair length determination with multiple indicators
     */
    private function determineHairLength($pet)
    {
        $nameLower = strtolower($pet->name);
        $descLower = strtolower($pet->description ?? '');
        $tempLower = strtolower($pet->temperament ?? '');
        
        // Comprehensive long-haired breed database
        $longHairBreeds = [
            // Dogs
            'afghan hound', 'shih tzu', 'maltese', 'yorkshire terrier', 'lhasa apso',
            'pomeranian', 'pekingese', 'havanese', 'cocker spaniel', 'golden retriever',
            'rough collie', 'shetland sheepdog', 'old english sheepdog', 'bearded collie',
            'tibetan terrier', 'portuguese water dog', 'bernese mountain dog',
            'newfoundland', 'samoyed', 'chow chow', 'keeshond', 'finnish lapphund',
            'briard', 'komondor', 'puli', 'bergamasco', 'leonberger', 'great pyrenees',
            'saint bernard', 'tibetan mastiff', 'english setter', 'gordon setter',
            'irish setter', 'cavalier king charles', 'papillon', 'american eskimo',
            'japanese chin', 'silky terrier', 'soft coated wheaten', 'border collie',
            'australian shepherd', 'springer spaniel', 'bichon frise',
            // Cats
            'persian', 'maine coon', 'ragdoll', 'himalayan', 'norwegian forest',
            'siberian', 'birman', 'turkish angora', 'ragamuffin', 'somali',
            'balinese', 'nebelung', 'turkish van', 'american curl', 'selkirk rex'
        ];
        
        // Short-haired breed database (for explicit matching)
        $shortHairBreeds = [
            // Dogs
            'beagle', 'boxer', 'bulldog', 'dachshund', 'dalmatian', 'doberman',
            'french bulldog', 'great dane', 'greyhound', 'labrador', 'pit bull',
            'pointer', 'pug', 'rottweiler', 'weimaraner', 'whippet', 'vizsla',
            'basenji', 'boston terrier', 'bull terrier', 'chihuahua', 'miniature pinscher',
            'rat terrier', 'staffordshire', 'jack russell', 'italian greyhound',
            // Cats
            'abyssinian', 'american shorthair', 'bengal', 'bombay', 'british shorthair',
            'burmese', 'cornish rex', 'devon rex', 'egyptian mau', 'exotic shorthair',
            'havana brown', 'korat', 'manx', 'ocicat', 'oriental shorthair',
            'russian blue', 'scottish fold', 'siamese', 'singapura', 'sphynx', 'tonkinese'
        ];
        
        // Check explicit short hair breeds first (higher priority)
        foreach ($shortHairBreeds as $breed) {
            if (stripos($nameLower, $breed) !== false) {
                return 'short';
            }
        }
        
        // Check explicit long hair breeds
        foreach ($longHairBreeds as $breed) {
            if (stripos($nameLower, $breed) !== false) {
                return 'long';
            }
        }
        
        // Score-based approach for description analysis
        $longHairScore = 0;
        $shortHairScore = 0;
        
        // Strong long hair indicators
        $longIndicators = [
            'long hair' => 3, 'long coat' => 3, 'flowing coat' => 3,
            'luxurious coat' => 2, 'silky coat' => 2, 'fluffy' => 2,
            'feathered' => 2, 'plumed' => 2, 'abundant coat' => 2,
            'double coat' => 1, 'thick coat' => 1, 'requires grooming' => 2,
            'needs brushing' => 2, 'daily grooming' => 2
        ];
        
        // Strong short hair indicators
        $shortIndicators = [
            'short hair' => 3, 'short coat' => 3, 'smooth coat' => 2,
            'sleek coat' => 2, 'easy to groom' => 2, 'low maintenance coat' => 2,
            'minimal grooming' => 2, 'tight coat' => 2
        ];
        
        $combinedText = $descLower . ' ' . $tempLower;
        
        foreach ($longIndicators as $phrase => $weight) {
            if (stripos($combinedText, $phrase) !== false) {
                $longHairScore += $weight;
            }
        }
        
        foreach ($shortIndicators as $phrase => $weight) {
            if (stripos($combinedText, $phrase) !== false) {
                $shortHairScore += $weight;
            }
        }
        
        // Make decision based on scores
        if ($longHairScore > $shortHairScore) {
            return 'long';
        } elseif ($shortHairScore > $longHairScore) {
            return 'short';
        }
        
        // Default: use heuristics based on breed type
        // Large breeds tend to have longer hair, toy breeds vary
        $sizeIndicators = ['toy', 'small', 'miniature'];
        $isSmallBreed = false;
        foreach ($sizeIndicators as $indicator) {
            if (stripos($nameLower, $indicator) !== false) {
                $isSmallBreed = true;
                break;
            }
        }
        
        // Default: short hair (more common), unless size suggests otherwise
        return 'short';
    }
    
    /**
     * Enhanced size determination with more accurate thresholds
     */
    private function determineSize($pet)
    {
        // Use the database size field as primary source
        $dbSize = $pet->size;
        
        // Enhanced mapping with more granular control
        $sizeMap = [
            'Toy' => 'small',
            'Small' => 'small',
            'Small to Medium' => 'small',  // Lean toward small
            'Medium' => 'medium',
            'Medium to Large' => 'large',   // Lean toward large for better matching
            'Large' => 'large',
            'Extra Large' => 'large',
            'Giant' => 'large',
        ];
        
        $mappedSize = $sizeMap[$dbSize] ?? 'medium';
        
        // Weight-based override if available
        $nameLower = strtolower($pet->name);
        $descLower = strtolower($pet->description ?? '');
        
        // Check for explicit size mentions that might override database
        if (preg_match('/(\d+)-(\d+)\s*pounds?/i', $descLower, $matches)) {
            $avgWeight = ($matches[1] + $matches[2]) / 2;
            
            if ($avgWeight < 25) {
                $mappedSize = 'small';
            } elseif ($avgWeight < 60) {
                $mappedSize = 'medium';
            } else {
                $mappedSize = 'large';
            }
        }
        
        // Breed name indicators (override if found)
        $smallIndicators = ['toy', 'miniature', 'mini', 'teacup', 'dwarf'];
        $largeIndicators = ['giant', 'mastiff', 'great', 'bernese', 'newfoundland', 'saint bernard'];
        
        foreach ($smallIndicators as $indicator) {
            if (stripos($nameLower, $indicator) !== false) {
                return 'small';
            }
        }
        
        foreach ($largeIndicators as $indicator) {
            if (stripos($nameLower, $indicator) !== false) {
                return 'large';
            }
        }
        
        return $mappedSize;
    }
    
    /**
     * Enhanced personality trait extraction with HEXACO mapping
     */
    private function extractPersonalityMatches($pet)
    {
        $temperamentLower = strtolower($pet->temperament ?? '');
        $descLower = strtolower($pet->description ?? '');
        $combinedText = $temperamentLower . ' ' . $descLower;
        
        $personalityScores = [
            'highHonesty' => 0,
            'highEmotionality' => 0,
            'highExtraversion' => 0,
            'highAgreeableness' => 0,
            'highConscientiousness' => 0,
            'highOpenness' => 0
        ];
        
        // Honesty-Humility indicators (loyal, trustworthy, dependable)
        $honestyIndicators = [
            'loyal' => 3, 'devoted' => 3, 'faithful' => 3, 'trustworthy' => 3,
            'reliable' => 2, 'dependable' => 2, 'honest' => 2, 'sincere' => 2,
            'protective' => 2, 'guardian' => 2
        ];
        
        // Emotionality indicators (sensitive, anxious, empathetic)
        $emotionalityIndicators = [
            'sensitive' => 3, 'empathetic' => 3, 'emotional' => 3, 'gentle' => 2,
            'shy' => 2, 'timid' => 2, 'anxious' => 2, 'nervous' => 1,
            'affectionate' => 2, 'loving' => 2, 'needs companionship' => 2,
            'bonds closely' => 2, 'velcro' => 3
        ];
        
        // Extraversion indicators (social, outgoing, energetic)
        $extraversionIndicators = [
            'friendly' => 3, 'outgoing' => 3, 'social' => 3, 'sociable' => 3,
            'gregarious' => 3, 'people-oriented' => 3, 'loves people' => 3,
            'energetic' => 2, 'active' => 2, 'playful' => 2, 'enthusiastic' => 2,
            'boisterous' => 2, 'lively' => 2, 'spirited' => 2
        ];
        
        // Agreeableness indicators (gentle, patient, cooperative)
        $agreeablenessIndicators = [
            'gentle' => 3, 'patient' => 3, 'kind' => 3, 'cooperative' => 3,
            'easy-going' => 3, 'tolerant' => 2, 'forgiving' => 2, 'calm' => 2,
            'peaceful' => 2, 'good with children' => 2, 'good with pets' => 2,
            'adaptable' => 2, 'flexible' => 2
        ];
        
        // Conscientiousness indicators (trainable, obedient, disciplined)
        $conscientiousnessIndicators = [
            'intelligent' => 3, 'smart' => 3, 'trainable' => 3, 'obedient' => 3,
            'quick learner' => 3, 'eager to please' => 2, 'responsive' => 2,
            'disciplined' => 2, 'focused' => 2, 'attentive' => 2, 'alert' => 2,
            'working dog' => 2, 'service dog' => 2
        ];
        
        // Openness indicators (curious, adaptable, adventurous)
        $opennessIndicators = [
            'curious' => 3, 'inquisitive' => 3, 'adventurous' => 3, 'exploratory' => 3,
            'adaptable' => 2, 'versatile' => 2, 'intelligent' => 2, 'clever' => 2,
            'problem solver' => 2, 'alert' => 1, 'aware' => 1, 'perceptive' => 2
        ];
        
        // Calculate scores
        $this->scoreTraits($combinedText, $honestyIndicators, $personalityScores, 'highHonesty');
        $this->scoreTraits($combinedText, $emotionalityIndicators, $personalityScores, 'highEmotionality');
        $this->scoreTraits($combinedText, $extraversionIndicators, $personalityScores, 'highExtraversion');
        $this->scoreTraits($combinedText, $agreeablenessIndicators, $personalityScores, 'highAgreeableness');
        $this->scoreTraits($combinedText, $conscientiousnessIndicators, $personalityScores, 'highConscientiousness');
        $this->scoreTraits($combinedText, $opennessIndicators, $personalityScores, 'highOpenness');
        
        // Sort by score and return top traits
        arsort($personalityScores);
        
        // Return top 3-4 traits (those with score > 0)
        $topTraits = [];
        foreach ($personalityScores as $trait => $score) {
            if ($score > 0 && count($topTraits) < 4) {
                $topTraits[] = $trait;
            }
        }
        
        return $topTraits;
    }
    
    /**
     * Helper to score traits
     */
    private function scoreTraits($text, $indicators, &$scores, $dimension)
    {
        foreach ($indicators as $phrase => $weight) {
            if (stripos($text, $phrase) !== false) {
                $scores[$dimension] += $weight;
            }
        }
    }
    
    /**
     * Format pet data with accurate characteristics
     */
    private function formatPetData($pet)
    {
        $mappedSize = $this->determineSize($pet);
        $hairLength = $this->determineHairLength($pet);
        $traits = array_slice(explode(', ', $pet->temperament), 0, 3);
        $personalityMatch = $this->extractPersonalityMatches($pet);
        
        // Ensure at least 2 personality traits
        if (count($personalityMatch) < 2) {
            // Add defaults based on pet category
            $defaults = $pet->category === 'dog' 
                ? ['highExtraversion', 'highAgreeableness', 'highConscientiousness']
                : ['highAgreeableness', 'highOpenness', 'highEmotionality'];
            
            foreach ($defaults as $default) {
                if (!in_array($default, $personalityMatch)) {
                    $personalityMatch[] = $default;
                    if (count($personalityMatch) >= 2) break;
                }
            }
        }
        
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
        // Check if reset parameter is present
        $resetDetected = false;
        if ($request->has('reset')) {
            $resetDetected = true;
            Log::debug('Reset parameter detected, clearing assessment results');
            Session::forget('assessment_results');
        }
        
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
        
        // Get dog and cat breeds from database with accurate formatting
        $dogBreeds = Pet::where('category', 'dog')->get()->map(function($pet) {
            return $this->formatPetData($pet);
        })->toArray();
        
        $catBreeds = Pet::where('category', 'cat')->get()->map(function($pet) {
            return $this->formatPetData($pet);
        })->toArray();

        // Get any saved assessment results from the session
        $savedResults = Session::get('assessment_results');
        
        // If no saved results in session but user is logged in, check for their most recent assessment
        if ((!$savedResults || empty($savedResults)) && auth()->check() && !$resetDetected) {
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
        
        // Normalize saved results with ACTUAL breed characteristics
        if (($savedResults['petType'] ?? null) && !empty($savedResults['recommendedBreeds'])) {
            foreach ($savedResults['recommendedBreeds'] as $index => $breed) {
                $petInfo = null;
                if (!empty($breed['id'])) {
                    $petInfo = Pet::find($breed['id']);
                }
                if (!$petInfo && !empty($breed['slug'])) {
                    $petInfo = Pet::where('slug', $breed['slug'])->first();
                }

                if ($petInfo) {
                    $savedResults['recommendedBreeds'][$index] = $this->formatPetData($petInfo);
                }
            }

            Session::put('assessment_results', $savedResults);
        }

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
                $fullBreedInfo[] = $this->formatPetData($petInfo);
            }
        }
        
        // Update the results with complete breed info
        $results['recommendedBreeds'] = $fullBreedInfo;
        
        // Save to session
        Session::put('assessment_results', $results);
        Session::flash('assessment_saved', true);
        
        // Save to database
        try {
            $personalityScores = $request->input('personalityScores', []);

            $assessment = Assessment::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'pet_type' => $petType,
                'preferences' => $results['preferences'] ?? [],
                // store the full breed info (array) into the results cast column
                'results' => $fullBreedInfo,
                'personality_scores' => $personalityScores,
            ]);

            Log::info('Assessment saved to database, id: ' . ($assessment->id ?? 'n/a'));

            return response()->json([
                'success' => true,
                'savedBreeds' => count($fullBreedInfo),
                'petType' => $results['petType'],
                'id' => $assessment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save assessment to database: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to save assessment: ' . $e->getMessage()], 500);
        }
    }
}