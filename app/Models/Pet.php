<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as HttpFile;

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

    // Add accessor for image URL (works with either relative path or absolute URL)
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        $path = ltrim($this->image, '/');
        return rtrim(env('AWS_URL', ''), '/') . '/' . $path;
    }

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

        // After create/update, if an image was uploaded to local 'public' disk,
        // move it to S3 and store only the S3 key in the DB. Avoid any ACL options.
        static::created(function ($breed) {
            self::moveLocalImageToS3($breed);
        });

        static::updated(function ($breed) {
            self::moveLocalImageToS3($breed);
        });

        static::deleting(function ($breed) {
            // Remove associated image from S3 when deleting record
            if (!empty($breed->image)) {
                try {
                    // Changed from 'b2' to 's3'
                    Storage::disk('s3')->delete($breed->image);
                } catch (\Throwable $e) {
                    // Swallow errors; optionally log
                    \Log::warning('Failed deleting S3 image for pet ID '.$breed->id.': '.$e->getMessage());
                }
            }
        });
    }

    /**
     * If the current image path is on the local public disk, move it to S3
     * and update the record to the S3 key. Skips if already an S3-style key
     * or if the local file doesn't exist.
     */
    protected static function moveLocalImageToS3(self $breed): void
    {
        $path = $breed->image ?? null;
        if (!$path) return;

        // If it's already an S3 key (e.g., starts with pets/ or form-attachments/), skip
        if (str_starts_with($path, 'pets/') || str_starts_with($path, 'form-attachments/')) {
            return;
        }

        // Normalize and check local public disk
        $localPath = ltrim($path, '/');
        if (!Storage::disk('public')->exists($localPath)) {
            return;
        }

        $fullLocal = Storage::disk('public')->path($localPath);
        $filename = basename($localPath);
        $s3Dir = 'pets';

        try {
            // Put to S3 without ACL/visibility options
            Storage::disk('s3')->putFileAs($s3Dir, new HttpFile($fullLocal), $filename);

            // Delete local temp file
            Storage::disk('public')->delete($localPath);

            // Update DB quietly
            $breed->image = $s3Dir . '/' . $filename;
            $breed->saveQuietly();
        } catch (\Throwable $e) {
            \Log::error('Failed moving image to S3', [
                'pet_id' => $breed->id,
                'path' => $localPath,
                'error' => $e->getMessage(),
            ]);
        }
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