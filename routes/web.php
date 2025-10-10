<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PrivateFileController;
use Illuminate\Support\Facades\Mail;

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
// Allow an optional {id} parameter so named routes can generate a stable path like /assessment/24
Route::get('/assessment/{id?}', [AssessmentController::class, 'index'])->name('assessment')->middleware(\App\Http\Middleware\CustomRedirectIfUnauthenticated::class);
Route::post('/assessment/save', [AssessmentController::class, 'saveResults'])->name('assessment.save');

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

// Debug route for testing profile updates
Route::get('/debug/profile-test', [\App\Http\Controllers\DebugController::class, 'testProfileUpdate'])
    ->middleware('auth')
    ->name('debug.profile-test');
    
// Static pages routes
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

Route::get('/test-email', function () {
    Mail::raw('This is a test email from Laravel!', function ($message) {
        $message->to('stiffendecastro48@gmail.com')
                ->subject('Test Email');
    });

    return 'Test email sent!';
});
