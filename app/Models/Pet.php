<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Accessor: $pet->image_url
     * Returns a full URL for the stored image on the configured disk (default now r2 in Filament resource).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // If already a full URL just return it
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Backblaze disk (S3 compatible). We named disk 'backblaze'.
        if (Storage::disk('backblaze')->exists($this->image)) {
            $endpoint = rtrim(config('filesystems.disks.backblaze.url') ?: config('filesystems.disks.backblaze.endpoint'), '/');
            $bucket = config('filesystems.disks.backblaze.bucket');
            // If disk visibility is private, caller should generate signed URL elsewhere; return path-style for now.
            if (config('filesystems.disks.backblaze.url')) {
                return $endpoint . '/' . ltrim($this->image, '/');
            }
            return $endpoint . '/' . $bucket . '/' . ltrim($this->image, '/');
        }

        $defaultDisk = config('filesystems.default');
        if ($defaultDisk && Storage::disk($defaultDisk)->exists($this->image)) {
            $rootUrl = rtrim(config("filesystems.disks.$defaultDisk.url") ?? url('storage'), '/');
            return $rootUrl . '/' . ltrim($this->image, '/');
        }

        if (Storage::disk('public')->exists($this->image)) {
            $publicUrl = rtrim(config('app.url'), '/') . '/storage';
            return $publicUrl . '/' . ltrim($this->image, '/');
        }

        return null;
    }
    // (Deprecated helper removed; use $pet->image_url)
}
