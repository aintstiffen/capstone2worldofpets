<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomRedirectIfUnauthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            // Redirect to our custom auth required page
            return response()->view('auth.auth-required');
        }

        return $next($request);
    }
}