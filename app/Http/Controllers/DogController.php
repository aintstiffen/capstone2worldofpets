<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        // Get all dog breeds with pagination (12 per page)
        $pets = Pet::where('category', 'dog')
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('dogs', compact('pets'));
    }
    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'dog')->firstOrFail();
        return view('dogs.show', compact('pet'));
    }
}
