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
            $redirectUrl = 'https://www.worldofpets.bond' . $request->getRequestUri();
            return redirect()->to($redirectUrl, 301);
        }

        return $next($request);
    }
}
