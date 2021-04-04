<?php

namespace App\Http\Controllers;

use App\Models\Monster;

class MonsterController extends Controller
{

    public function index()
    {
        $monsters = Monster::all();

        return response()->json([
            'error' =>  false,
            'msg'   =>  '',
            'data'  =>  $monsters
        ]);
    }

}
