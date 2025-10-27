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
        // renamed fields to match Filament form
        'average_weight',
        'price_range',
        'lifespan',
        'energy_level',
        'friendliness',
        'origin',
        'exerciseNeeds',
        'grooming',
        'description',
        'category',
        'image',
        'hotspots',
        'fun_facts',
        'gif_url',
        'gallery',
        'color_images',
        'diet_images',
        'avoid_title',
        'avoid_description',
    ];
    
    protected $casts = [
        'color_images' => 'array',
        'diet_images' => 'array',
        'hotspots' => 'array',
        'fun_facts' => 'array',
        'gallery' => 'array',
    ];
    
    /**
     * Return an associative mapping of color name => public URL for the uploaded images.
     */
    public function getColorImageUrlsAttribute()
    {
        $items = $this->color_images;
        if (!$items || !is_array($items)) return [];

        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $name = $item['name'] ?? null;
            $path = $item['image'] ?? null;
            if (!$name || !$path) continue;

            $result[$name] = $this->resolveUrl($path);
        }

        return $result;
    }

    /**
     * Return an associative mapping of diet name => public URL
     */
    public function getDietImageUrlsAttribute()
    {
        $items = $this->diet_images;
        if (!$items || !is_array($items)) return [];

        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $name = $item['name'] ?? null;
            $path = $item['image'] ?? null;
            if (!$name || !$path) continue;

            $result[$name] = $this->resolveUrl($path);
        }
        return $result;
    }
    public function getGalleryImageUrlsAttribute()
{
    $items = $this->gallery;
    if (!$items || !is_array($items)) return [];

        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            // support both 'url' (new) and 'image' (legacy) keys
            $path = $item['url'] ?? $item['image'] ?? null;
            if (!$path) continue;

            $result[] = $this->resolveUrl($path);
        }

        return $result;
}

    /**
     * Helper method to resolve file path to full URL
     */
    protected function resolveUrl($path)
    {
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }

        try {
            $disk = config('filesystems.default', env('FILESYSTEM_DISK', 's3'));
            $storageDisk = \Illuminate\Support\Facades\Storage::disk($disk);
            /** @var \Illuminate\Filesystem\FilesystemAdapter $storageDisk */
            if (method_exists($storageDisk, 'url')) {
                return $storageDisk->url($path);
            }

            // Fallback: return the path if the disk adapter doesn't expose a url() method
            return $path;
        } catch (\Throwable $e) {
            \Log::error('Failed to resolve URL for path: ' . $path, ['error' => $e->getMessage()]);
            return $path;
        }
    }

    /**
     * Accessor for image URL - supports direct URLs or legacy stored paths
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image;
        }
        
        return $this->resolveUrl($this->image);
    }

    // Auto-generate slug if not provided
    protected static function booted()
    {
        static::creating(function ($breed) {
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
            
            if (!empty($breed->hotspots) && is_array($breed->hotspots)) {
                $breed->hotspots = self::validateUniqueFeatures($breed->hotspots);
            }
            
            if (!empty($breed->fun_facts) && is_array($breed->fun_facts)) {
                $breed->fun_facts = self::validateUniqueFeatures($breed->fun_facts);
            }
        });

        static::updating(function ($breed) {
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
            
            if (!empty($breed->hotspots) && is_array($breed->hotspots)) {
                $breed->hotspots = self::validateUniqueFeatures($breed->hotspots);
            }
            
            if (!empty($breed->fun_facts) && is_array($breed->fun_facts)) {
                $breed->fun_facts = self::validateUniqueFeatures($breed->fun_facts);
            }
        });
    }
    
    /**
     * Ensure that features are unique in arrays like hotspots and fun_facts
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