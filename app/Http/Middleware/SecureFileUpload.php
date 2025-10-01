<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureFileUpload
{
    /**
     * Handle an incoming request for file uploads with enhanced security.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to file upload requests
        if (!$request->hasFile('file') && !$request->is('livewire/upload-file')) {
            return $next($request);
        }

        // Rate limiting for uploads
        $user = Auth::user();
        if ($user) {
            $cacheKey = 'upload_attempts_' . $user->id;
            $attempts = cache()->get($cacheKey, 0);
            
            if ($attempts > 10) { // Max 10 uploads per minute
                Log::warning('Upload rate limit exceeded', [
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);
                return response()->json(['error' => 'Upload rate limit exceeded'], 429);
            }
            
            cache()->put($cacheKey, $attempts + 1, 60); // 1 minute
        }

        // Log upload attempts for security monitoring
        if ($request->hasFile('file')) {
            Log::info('File upload attempt', [
                'user_id' => $user ? $user->id : null,
                'ip' => $request->ip(),
                'file_size' => $request->file('file')->getSize(),
                'mime_type' => $request->file('file')->getMimeType(),
            ]);
        }

        return $next($request);
    }
}