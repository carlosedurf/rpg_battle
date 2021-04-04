<?php

namespace App\Http\Controllers;

use App\Models\Classification;

class ClassificationController extends Controller
{

    public function index()
    {
        $classifications = Classification::all();

        return response()->json([
            'error' =>  false,
            'msg'   =>  '',
            'data'  =>  $classifications
        ]);
    }

}
