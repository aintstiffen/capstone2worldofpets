<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('homepage');
});

// Health check route for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'environment' => app()->environment(),
        'database' => function() { try { \DB::connection()->getPdo(); return 'connected'; } catch (\Throwable $e) { return 'error: '.$e->getMessage(); } },
    ]);
});

// Test route for pet facts
Route::get('/test-dog-facts', function () {
    return response()->file(public_path('dog-facts-test.html'));
});

Route::get('/test-pet-facts', function () {
    return response()->file(public_path('pet-facts-test.html'));
});

// Handle GET requests to logout (redirect them properly)
Route::get('/logout', function () {
    return redirect()->route('login')->with('error', 'Please use the logout button to log out properly.');
});

// Include authentication routes
require __DIR__.'/auth.php';

// Pet routes
Route::get('/dogs', [DogController::class, 'index'])->name('dogs');
Route::get('/cats', [CatController::class, 'index'])->name('cats');
Route::get('/dogs/{slug}', [DogController::class, 'show'])->name('dogs.show');
Route::get('/cats/{slug}', [CatController::class, 'show'])->name('cats.show');

// Assessment routes
Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment')->middleware(\App\Http\Middleware\CustomRedirectIfUnauthenticated::class);
Route::post('/assessment/save', [AssessmentController::class, 'saveResults'])->name('assessment.save')->middleware('auth');

// Compare routes (Authentication required)
Route::get('/compare', [\App\Http\Controllers\CompareController::class, 'index'])->name('compare')->middleware(\App\Http\Middleware\CustomRedirectIfUnauthenticated::class);
Route::get('/compare/breeds/{type}', [\App\Http\Controllers\CompareController::class, 'getBreeds'])->name('compare.breeds')->middleware('auth');
Route::post('/compare', [\App\Http\Controllers\CompareController::class, 'compare'])->name('compare.submit')->middleware('auth');

// System information route
Route::get('/system-info', function () {
    return [
        'admin_user_count' => \App\Models\Admin::count(),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'filament_version' => \Composer\InstalledVersions::getVersion('filament/filament'),
        'environment' => app()->environment(),
        'admin_panel_id' => config('filament.default_panel_id', 'admin'),
        'cache_driver' => config('cache.default'),
        'session_driver' => config('session.driver'),
    ];
});

// Proxy route to serve private B2 images (avoids browser-side CORS with presigned URLs in UI tables)
Route::get('/b2/image/{path}', function (string $path) {
    $disk = Storage::disk('b2');
    $fullPath = request()->route('path');
    if (! $disk->exists($fullPath)) {
        abort(404);
    }
    $contents = $disk->get($fullPath);
    $finfo = function_exists('finfo_buffer') ? new \finfo(FILEINFO_MIME_TYPE) : null;
    $mime = $finfo ? ($finfo->buffer($contents) ?: 'application/octet-stream') : 'application/octet-stream';
    return response($contents, 200)
        ->header('Content-Type', $mime)
        ->header('Cache-Control', 'public, max-age=300');
})->where('path', '.*')->name('b2.image');

// Test routes for debugging B2 storage issues
Route::get('/test-b2', function () {
    try {
        $b2Disk = Storage::disk('b2');
        
        // Test writing a simple file
        $testContent = 'Test file content - ' . now();
        $testPath = 'test/test-' . time() . '.txt';
        
        $writeSuccess = $b2Disk->put($testPath, $testContent);
        
        if ($writeSuccess) {
            // Try to read it back
            $readContent = $b2Disk->get($testPath);
            
            if ($readContent === $testContent) {
                // Clean up test file
                $b2Disk->delete($testPath);
                return response()->json([
                    'status' => 'success',
                    'message' => 'B2 storage is working correctly',
                    'test_path' => $testPath
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could read from B2 but content mismatch',
                    'expected' => $testContent,
                    'actual' => $readContent
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to write to B2 storage'
            ], 500);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'B2 connection error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/test-livewire-upload', function () {
    try {
        // Test the public disk where Livewire temporarily stores files
        $publicDisk = Storage::disk('public');
        
        return response()->json([
            'status' => 'success',
            'livewire_tmp_exists' => $publicDisk->exists('livewire-tmp'),
            'livewire_tmp_files' => $publicDisk->allFiles('livewire-tmp'),
            'public_disk_root' => $publicDisk->path(''),
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Livewire temp disk error: ' . $e->getMessage()
        ], 500);
    }
});

// Fallback: prevent noisy exception when bots or accidental GET hit Livewire's POST-only upload route
Route::get('livewire/upload-file', function () {
    return response()->json(['message' => 'Not found'], 404);
});