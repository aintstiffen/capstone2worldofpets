<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
class CatController extends Controller
{
      public function index()
    {
        // Get all cat breeds without pagination
        $petss = Pet::where('category', 'cat')->orderBy('id', 'desc')->get();

        return view('cats', compact('petss'));
    }

    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'cat')->firstOrFail();
        return view('cats.show', compact('pet'));
    }
}
