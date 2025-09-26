<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugUpload
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log all upload requests for debugging
        if ($request->is('livewire/upload-file')) {
            Log::info('Livewire upload request received', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'has_file' => $request->hasFile('file'),
                'file_count' => $request->allFiles() ? count($request->allFiles()) : 0,
                'headers' => $request->headers->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }

        $response = $next($request);

        // Log response status
        if ($request->is('livewire/upload-file')) {
            Log::info('Livewire upload response', [
                'status' => $response->getStatusCode(),
                'content_type' => $response->headers->get('Content-Type'),
            ]);
        }

        return $response;
    }
}