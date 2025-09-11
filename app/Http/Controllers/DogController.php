<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        // Get all dog breeds without pagination
        $pets = Pet::where('category', 'dog')->orderBy('id', 'desc')->get();

        return view('dogs', compact('pets'));
    }
    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'dog')->firstOrFail();
        return view('dogs.show', compact('pet'));
    }
}
