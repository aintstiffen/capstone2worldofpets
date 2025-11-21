<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        // Get all dog breeds with pagination (4 per page)
        $pets = Pet::where('category', 'dog')
            ->orderBy('name', 'asc')
            ->paginate(4);

        return view('dogs', compact('pets'));
    }
    public function show($slug)
    {
        $pet = Pet::where('slug', $slug)->where('category', 'dog')->firstOrFail();

        // Ensure gallery is an array so the view can safely iterate/count it
        if (is_null($pet->gallery)) {
            $pet->gallery = [];
        }

        return view('dogs.show', compact('pet'));
    }
}
