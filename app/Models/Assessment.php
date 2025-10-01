<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_type',
        'preferences',
        'results',
        'personality_scores',
    ];

    protected $casts = [
        'preferences' => 'array',
        'results' => 'array',
        'personality_scores' => 'array',
    ];
    
    /**
     * Validation rules for assessment data
     */
    public static $rules = [
        'pet_type' => 'required|string|in:dog,cat',
        'preferences' => 'required|array',
        'results' => 'required|array',
        'personality_scores' => 'nullable|array',
    ];
    
    /**
     * Boot function to add validation before saving
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($assessment) {
            if (!in_array($assessment->pet_type, ['dog', 'cat'])) {
                throw new \InvalidArgumentException('Pet type must be either dog or cat');
            }
            
            if (!is_array($assessment->preferences)) {
                throw new \InvalidArgumentException('Preferences must be an array');
            }
            
            if (!is_array($assessment->results) || empty($assessment->results)) {
                throw new \InvalidArgumentException('Results must be a non-empty array');
            }
        });
    }

    /**
     * Get the user who took the assessment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}