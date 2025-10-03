<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleLivewireUploads
{
    public function handle(Request $request, Closure $next)
    {
        // Skip CSRF for Livewire uploads
        if ($request->is('livewire/upload-file') || $request->is('livewire/upload-file/*')) {
            $request->attributes->add(['csrf_exempt' => true]);
        }
        
        return $next($request);
    }
}