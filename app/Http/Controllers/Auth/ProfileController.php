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
        $user = $request->user();
        $avatarStyles = \App\Models\User::getAvatarStyles();
        
        return view('auth.profile', [
            'user' => $user,
            'avatarStyles' => $avatarStyles,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        \Log::info('Profile update requested', [
            'has_file' => $request->hasFile('profile_picture'),
            'all_data' => $request->all()
        ]);

        // Log all request data
        \Log::info('Profile update all request data', [
            'all_data' => $request->all(),
            'has_avatar_style' => $request->has('avatar_style'),
            'avatar_style' => $request->input('avatar_style'),
        ]);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'avatar_style' => ['nullable', 'string', Rule::in(array_keys(\App\Models\User::getAvatarStyles()))],
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
            
            // Handle profile picture upload first
            if ($request->hasFile('profile_picture')) {
                \Log::info('Profile picture file detected');
                
                // Delete old profile picture if it exists
                if ($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))) {
                    unlink(public_path('storage/' . $user->profile_picture));
                }
                
                try {
                    // Store new profile picture with original filename
                    $file = $request->file('profile_picture');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $profilePicPath = $file->storeAs('profile-pictures', $filename, 'public');
                    \Log::info('Profile picture stored at: ' . $profilePicPath);
                    
                    // Set the profile picture path directly and force the update
                    $user->forceFill(['profile_picture' => $profilePicPath]);
                    \Log::info('Profile picture path set in user model: ' . $user->profile_picture);
                } catch (\Exception $e) {
                    \Log::error('Error storing profile picture: ' . $e->getMessage());
                    throw $e;
                }
            } else {
                \Log::info('No profile picture file in request');
            }

            // Update user information with detailed logging
            \Log::info('Before update', [
                'current_name' => $user->name,
                'new_name' => $validated['name'],
                'current_avatar_style' => $user->avatar_style,
                'new_avatar_style' => $request->input('avatar_style'),
            ]);
            
            // Debug flag processing
            if ($request->has('debug')) {
                \Log::alert('Debug mode enabled - form data', [
                    'all_data' => $request->all(),
                    'input_name' => $request->input('name'),
                    'input_avatar_style' => $request->input('avatar_style')
                ]);
            }
            
            // Directly update fields one by one
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            // Always attempt to set avatar style from the request
            $avatarStyle = $request->input('avatar_style');
            if ($avatarStyle) {
                $user->forceFill(['avatar_style' => $avatarStyle]);
                \Log::info('Avatar style update forced', [
                    'style' => $avatarStyle,
                    'user_id' => $user->id
                ]);
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            
            // Log what will be saved
            \Log::info('Changes to be saved', [
                'name' => $user->name,
                'avatar_style' => $user->avatar_style,
                'dirty_fields' => $user->getDirty()
            ]);

            // Save all changes and verify
            $saved = $user->save();
            
            \Log::info('Name update attempt', [
                'old_name' => $user->getOriginal('name'),
                'new_name' => $validated['name'],
                'save_result' => $saved
            ]);
            
            // Verify the save
            \Log::info('Profile update completed', [
                'save_success' => $saved,
                'profile_picture_after_save' => $user->fresh()->profile_picture,
                'user_id' => $user->id
            ]);

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return Redirect::route('profile.edit')->withErrors([
                'profile_error' => 'There was an error updating your profile: ' . $e->getMessage()
            ]);
        }
    }
}