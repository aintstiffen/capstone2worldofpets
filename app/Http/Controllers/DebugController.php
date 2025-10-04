<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    public function testProfileUpdate(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Get the original values
        $originalName = $user->name;
        $originalAvatarStyle = $user->avatar_style;
        
        // Set new test values
        $user->name = 'Test Name ' . time();
        $user->avatar_style = array_rand(\App\Models\User::getAvatarStyles());
        
        // Save the changes
        $saved = $user->save();
        
        // Refresh from database
        $user->refresh();
        
        return response()->json([
            'success' => $saved,
            'original' => [
                'name' => $originalName,
                'avatar_style' => $originalAvatarStyle,
            ],
            'new' => [
                'name' => $user->name,
                'avatar_style' => $user->avatar_style,
            ],
            'user_id' => $user->id,
            'changed' => $originalName !== $user->name || $originalAvatarStyle !== $user->avatar_style
        ]);
    }
}