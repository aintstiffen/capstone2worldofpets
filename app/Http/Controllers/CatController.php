<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
class CatController extends Controller
{
      public function index()
    {
        // Get all cat breeds with pagination (4 per page)
        $petss = Pet::where('category', 'cat')
            ->orderBy('name', 'asc')
            ->paginate(4);

        return view('cats', compact('petss'));
    }

    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'cat')->firstOrFail();
        // Eloquent already casts gallery to array via $casts in Pet model
        if (is_null($pet->gallery)) {
            $pet->gallery = [];
        }
        return view('cats.show', compact('pet'));
    }
}
