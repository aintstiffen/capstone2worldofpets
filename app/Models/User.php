<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'avatar_style',
    ];

    /**
     * Get the available avatar styles
     * 
     * @return array
     */
    public static function getAvatarStyles()
    {
        return [
            'bottts' => 'https://api.dicebear.com/7.x/bottts/svg?seed=',  // Robot style
            'adventurer' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=', // Adventure style
            'fun-emoji' => 'https://api.dicebear.com/7.x/fun-emoji/svg?seed=', // Fun emoji style
            'pixel-art' => 'https://api.dicebear.com/7.x/pixel-art/svg?seed=', // Pixel art style
            'identicon' => 'https://api.dicebear.com/7.x/identicon/svg?seed=', // Abstract style
        ];
    }
    
    /**
     * Get the URL for the user's profile picture
     *
     * @return string
     */
    public function getProfilePictureUrl()
    {
        // Use seed based on user ID to keep the same avatar for the same user
        $seed = md5($this->id . $this->name);
        
        // Get available avatar styles
        $styles = self::getAvatarStyles();
        
        // If user has selected a style, use it, otherwise pick one based on their ID
        if ($this->avatar_style && isset($styles[$this->avatar_style])) {
            return $styles[$this->avatar_style] . $seed;
        }
        
        // Use the first characters of the seed to pick a default style
        $styleKeys = array_keys($styles);
        $styleIndex = hexdec(substr($seed, 0, 2)) % count($styles);
        
        // Return the URL with the seed
        return $styles[$styleKeys[$styleIndex]] . $seed;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the assessments for the user.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
    
}
