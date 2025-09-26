<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateFileController extends Controller
{
    /**
     * Stream a file from private B2 disk.
     * Path is the relative path stored in DB, e.g. 'pets/abc.jpg'
     */
    public function show($path)
    {
        $disk = Storage::disk('b2');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $stream = $disk->readStream($path);
        if ($stream === false) {
            abort(500);
        }

        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $size = $disk->size($path);

        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            // Long-lived client cache (optional — see note below)
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
