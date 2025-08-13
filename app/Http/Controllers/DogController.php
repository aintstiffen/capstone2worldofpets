<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        // If you have a column that marks dogs (e.g. `type` or `category`), filter:
        $pets = Pet::where('category', 'dog')->orderBy('id', 'desc')->paginate(12);

        // If no type column, use all pets:
        // $pets = Pet::paginate(12);

        return view('dogs', compact('pets'));
    }
    public function show($id)
    {
        $pet = Pet::findOrFail($id); // Or Pet::where('slug', $slug)->firstOrFail();
        return view('dogs.show', compact('pet'));
    }
}
