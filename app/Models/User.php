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
    ];

    /**
     * Get the URL for the user's profile picture
     *
     * @return string
     */
    public function getProfilePictureUrl()
    {
        // Use seed based on user ID to keep the same avatar for the same user
        $seed = md5($this->id . $this->name);
        
        // Create an array of available pet avatar styles
        $styles = [
            'https://api.dicebear.com/7.x/bottts/svg?seed=',  // Robot style
            'https://api.dicebear.com/7.x/adventurer/svg?seed=', // Adventure style
            'https://api.dicebear.com/7.x/fun-emoji/svg?seed=', // Fun emoji style
        ];
        
        // Use the first characters of the seed to pick a style
        $styleIndex = hexdec(substr($seed, 0, 2)) % count($styles);
        
        // Return the URL with the seed
        return $styles[$styleIndex] . $seed;
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
