<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AssessmentController;

Route::get('/', function () {
    return view('homepage');
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
Route::get('/dogs', function () {
    return view('dogs');
})->name('dogs');
Route::get('/cats', function () {
    return view('cats');
})->name('cats');
Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment')->middleware(\App\Http\Middleware\CustomRedirectIfUnauthenticated::class);
Route::post('/assessment/save', [AssessmentController::class, 'saveResults'])->name('assessment.save')->middleware('auth');
Route::get('/dogs', [DogController::class, 'index'])->name('dogs');
Route::get('/cats', [CatController::class, 'index'])->name('cats');
Route::get('/dogs/{slug}', [DogController::class, 'show'])->name('dogs.show');
Route::get('/cats/{slug}', [CatController::class, 'show'])->name('cats.show');

// Compare routes
Route::get('/compare', [\App\Http\Controllers\CompareController::class, 'index'])->name('compare');
Route::get('/compare/breeds/{type}', [\App\Http\Controllers\CompareController::class, 'getBreeds'])->name('compare.breeds');
Route::post('/compare', [\App\Http\Controllers\CompareController::class, 'compare'])->name('compare.submit');