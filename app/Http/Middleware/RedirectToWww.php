<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectToWww
{
    public function handle(Request $request, Closure $next)
    {
        // Only redirect in production environment and for specific domain
        if (app()->environment('production') && $request->getHost() === 'worldofpets.bond') {
            // Do NOT redirect Livewire internal endpoints (e.g. temporary upload route)
            // Redirecting these signed POST requests can cause the browser to retry as GET
            // and/or invalidate the signature, resulting in 401/MethodNotAllowed errors.
            if ($request->is('livewire/*')) {
                return $next($request);
            }

            // Prefer 308 (Permanent Redirect) to preserve HTTP method for other POST routes
            $redirectUrl = 'https://www.worldofpets.bond' . $request->getRequestUri();
            return redirect()->to($redirectUrl, 308);
        }

        return $next($request);
    }
}
