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
        'hotspots',
        'fun_facts',
        'gif_url',
        'gallery',
        'color_images',
    ];

    protected $casts = [
        'colors' => 'array',
        'color_images' => 'array',
        'hotspots' => 'array',
        'fun_facts' => 'array',
        'gallery' => 'array',
    ];

    /**
     * Return an associative mapping of color name => public URL for the uploaded images.
     * Normalizes stored paths to full URLs (Storage::url) when necessary.
     *
     * Example return: ['Black' => 'https://.../color_images/black.jpg']
     */
    public function getColorImagesAttribute($value)
    {
        $items = $this->attributes['color_images'] ?? null;
        if (!$items) return null;

        // If already stored as JSON string, decode
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }

        if (!is_array($items)) return null;

        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $name = $item['name'] ?? null;
            $path = $item['image'] ?? null;
            if (!$name || !$path) continue;

            // If the path looks like a full URL, keep it, otherwise convert using Storage::url
            if (preg_match('/^https?:\/\//', $path)) {
                $url = $path;
            } else {
                try {
                    // If S3 is configured, attempt to construct an S3 URL from config.
                    $s3Config = config('filesystems.disks.s3');
                    if (!empty($s3Config) && !empty($s3Config['bucket'])) {
                        if (!empty($s3Config['endpoint'])) {
                            $endpoint = rtrim($s3Config['endpoint'], '/');
                            $url = $endpoint . '/' . ltrim($path, '/');
                        } else {
                            // Region-aware default endpoint
                            $region = $s3Config['region'] ?? null;
                            $bucket = $s3Config['bucket'];
                            if ($region) {
                                $url = "https://{$bucket}.s3.{$region}.amazonaws.com/" . ltrim($path, '/');
                            } else {
                                $url = "https://{$bucket}.s3.amazonaws.com/" . ltrim($path, '/');
                            }
                        }
                    } else {
                        // Fall back to the framework Storage helper which uses the default disk
                        $url = \Illuminate\Support\Facades\Storage::url($path);
                    }
                } catch (\Throwable $e) {
                    $url = $path; // fallback
                }
            }

            $result[$name] = $url;
        }

        return $result;
    }

    // Accessor for image URL - supports direct URLs or legacy stored paths
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        // If it's already a full URL, return as-is
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image;
        }
        // Legacy fallback: if an old relative path remains, just return it unchanged
        // so front-end or columns can decide how to handle it. You may migrate later.
        return $this->image;
    }

    // Auto-generate slug if not provided
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
            // Optional: keep slug synced if you want (or remove to keep manual)
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

        // No storage-side deletion needed anymore; images are external URLs now.
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