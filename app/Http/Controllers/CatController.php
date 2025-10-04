<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
class CatController extends Controller
{
      public function index()
    {
        // Get all cat breeds with pagination (12 per page)
        $petss = Pet::where('category', 'cat')
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('cats', compact('petss'));
    }

    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'cat')->firstOrFail();
        return view('cats.show', compact('pet'));
    }
}
