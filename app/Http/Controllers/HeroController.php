<?php

namespace App\Http\Controllers;

use App\Models\Hero;

class HeroController extends Controller
{

    public function index()
    {
        $heroes = Hero::all();

        return response()->json([
            'data' => $heroes
        ], 200);
    }

}
