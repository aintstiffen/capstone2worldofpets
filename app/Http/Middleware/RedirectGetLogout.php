<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectGetLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If this is a GET request to the logout route, redirect to home
        if ($request->isMethod('get') && $request->is('logout')) {
            return redirect('/')->with('message', 'Please use the logout button to log out.');
        }

        return $next($request);
    }
}