<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel as FilamentPanel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function profilePhotoUrl(): ?string
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        // Prefer R2 public base URL if configured, else fallback to Storage facade
        $base = config('filesystems.disks.r2.url');
        if ($base) {
            return rtrim($base, '/').'/'.ltrim($this->profile_photo_path, '/');
        }
        $endpoint = rtrim(config('filesystems.disks.r2.endpoint'), '/');
        $bucket = config('filesystems.disks.r2.bucket');
        return $endpoint.'/'.$bucket.'/'.$this->profile_photo_path;
    }

    /**
     * Allow all admins to access any Filament panel for now.
     * You can later add role/permission checks here.
     */
    public function canAccessPanel(FilamentPanel $panel): bool
    {
        return true;
    }
}