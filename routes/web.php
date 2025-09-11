<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AssessmentController;

Route::get('/', function () {
    return view('homepage');
});
Route::get('/dogs', function () {
    return view('dogs');
})->name('dogs');
Route::get('/cats', function () {
    return view('cats');
})->name('cats');
Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment');
Route::post('/assessment/save', [AssessmentController::class, 'saveResults'])->name('assessment.save');
Route::get('/dogs', [DogController::class, 'index'])->name('dogs');
Route::get('/cats', [CatController::class, 'index'])->name('cats');
Route::get('/dogs/{slug}', [DogController::class, 'show'])->name('dogs.show');
Route::get('/cats/{slug}', [CatController::class, 'show'])->name('cats.show');