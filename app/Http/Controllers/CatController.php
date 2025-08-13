<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
class CatController extends Controller
{
      public function index()
    {
        // If you have a column that marks dogs (e.g. `type` or `category`), filter:
        $petss = Pet::where('category', 'cat')->orderBy('id', 'desc')->paginate(12);

        // If no type column, use all pets:
        // $pets = Pet::paginate(12);

        return view('cats', compact('petss'));
    }
}
