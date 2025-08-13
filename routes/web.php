<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\CatController;


Route::get('/', function () {
    return view('homepage');
});
Route::get('/dogs', function () {
    return view('dogs');
})->name('dogs');
Route::get('/cats', function () {
    return view('cats');
})->name('cats');
Route::get('/assessment', function () {
    return view('assessment');
})->name('assessment');
Route::get('/dogs', [DogController::class, 'index'])->name('dogs');
Route::get('/cats', [CatController::class, 'index'])->name('cats');
Route::get('/dogs/{id}', [DogController::class, 'show'])->name('dogs.show');