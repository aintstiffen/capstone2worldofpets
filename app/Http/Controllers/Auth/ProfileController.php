<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('auth.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            // First, check if the profile_picture column exists
            if (!Schema::hasColumn('users', 'profile_picture')) {
                // If it doesn't exist, add it directly
                Schema::table('users', function ($table) {
                    $table->string('profile_picture')->nullable();
                });
                
                // Add a log entry
                \Log::info('Added missing profile_picture column to users table during profile update');
            }
            
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if it exists
                if ($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))) {
                    unlink(public_path('storage/' . $user->profile_picture));
                }
                
                // Store new profile picture
                $profilePicPath = $request->file('profile_picture')->store('profile-pictures', 'public');
                $user->profile_picture = $profilePicPath;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return Redirect::route('profile.edit')->withErrors([
                'profile_error' => 'There was an error updating your profile: ' . $e->getMessage()
            ]);
        }
    }
}