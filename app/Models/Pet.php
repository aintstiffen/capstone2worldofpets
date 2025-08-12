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
    ];

    // auto-generate slug if not provided
    protected static function booted()
    {
        static::creating(function ($breed) {
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
        });

        static::updating(function ($breed) {
            // optional: keep slug synced if you want (or remove to keep manual)
            if (empty($breed->slug) && !empty($breed->name)) {
                $breed->slug = Str::slug($breed->name);
            }
        });
    }
}
