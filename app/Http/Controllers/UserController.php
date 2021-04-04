<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Battle,
    Hero,
    User
};

class UserController extends Controller
{

    public function show(User $user)
    {

        return response()->json([
            'error' => false,
            'msg'   => '',
            'data'  =>  $user
        ]);
    }

    public function battles(User $user)
    {
        $battles = $user->battles;

        return response()->json([
            'error' => false,
            'msg'   => '',
            'data'  =>  $battles
        ]);
    }

    public function store(Request $request)
    {
        $nick = $request->get('nick');

        if(empty($nick))
            return response()->json(['error' => true, 'msg' => 'Field nick empty'], 400);

        $user = new User();
        $user->nick = $nick;
        $user->save();

        $heroes = Hero::all();

        return response()->json([
            'error' => false,
            'msg'   =>  '',
            'data'  => [
                'heroes'    =>  $heroes,
                'user'      =>  $user,
            ]
        ], 200);
    }

}
