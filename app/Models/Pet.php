<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pet extends Model
{
    use HasFactory;
    protected $table = 'pets';
    protected $fillable = [
        'name',
        'slug',
        'size',
        'temperament',
        'lifespan',
        'energy',
        'friendliness',
        'trainability',
        'exerciseNeeds',
        'grooming',
        'colors',
        'description',
        'category',
        'image',
    ];

    protected $casts = [
        'colors' => 'array',
        'hotspots' => 'array',
        'fun_facts' => 'array',
    ];

    // auto-generate slug if not provided
    protected static function booted()
    {
        static::creating(function ($breed) {
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
            
            // Validate hotspots for unique features
            if (!empty($breed->hotspots) && is_array($breed->hotspots)) {
                $breed->hotspots = self::validateUniqueFeatures($breed->hotspots);
            }
            
            // Validate fun_facts for unique features
            if (!empty($breed->fun_facts) && is_array($breed->fun_facts)) {
                $breed->fun_facts = self::validateUniqueFeatures($breed->fun_facts);
            }
        });

        static::updating(function ($breed) {
            // optional: keep slug synced if you want (or remove to keep manual)
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
            
            // Validate hotspots for unique features
            if (!empty($breed->hotspots) && is_array($breed->hotspots)) {
                $breed->hotspots = self::validateUniqueFeatures($breed->hotspots);
            }
            
            // Validate fun_facts for unique features
            if (!empty($breed->fun_facts) && is_array($breed->fun_facts)) {
                $breed->fun_facts = self::validateUniqueFeatures($breed->fun_facts);
            }
        });
    }
    
    /**
     * Ensure that features are unique in arrays like hotspots and fun_facts
     * 
     * @param array $items Array of items with 'feature' key
     * @return array Filtered array with only unique features
     */
    protected static function validateUniqueFeatures(array $items): array
    {
        $uniqueFeatures = [];
        $result = [];
        
        foreach ($items as $item) {
            if (isset($item['feature']) && !in_array($item['feature'], $uniqueFeatures)) {
                $uniqueFeatures[] = $item['feature'];
                $result[] = $item;
            }
        }
        
        return $result;
    }
}
