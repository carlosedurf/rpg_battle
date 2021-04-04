<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\BattleStep;
use App\Models\{
    Battle,
    Monster,
    User,
    BattleStep as ModelsBattleStep,
    Classification
};

class BattleController extends Controller
{

    use BattleStep;

    public function start(Request $request)
    {
        // Verifica se foi enviado o user_id
        if(!$request->hasHeader('user_id') || empty($request->header('user_id')))
            return response()->json([
                'error' => true,
                'msg'   => 'header user_id not found'
            ], 400);


        // verifica se foi enviado o hero_id
        if(!$request->has('hero_id') || empty($request->get('hero_id')))
            return response()->json([
                'error' => true,
                'msg'   => 'hero_id not found'
            ], 400);


        // Verica se existe usuario cadastrado
        $user = User::find($request->header('user_id'));
        if(!$user)
            return response()->json([
                'error' => true,
                'msg'   => 'user not found in database'
            ], 400);


        // Verificar se existe batalaha em aberto
        if(count($user->battles()->whereStep('in_progress')->get()))
            return response()->json([
                'error' => true,
                'msg'   => 'exists battle in progress for this user'
            ], 400);


        // Gera monstro aleatório
        $monster = Monster::all()->random()->first();

        // Salva batalha
        $battle = new Battle();
        $battle->user_id    = $user->id;
        $battle->hero_id    = $request->get('hero_id');
        $battle->monster_id = $monster->id;

        $battle->save();

        $battle->user       = $battle->user;
        $battle->hero       = $battle->hero;
        $battle->monster    = $battle->monster;

        return response()->json($battle, 200);
    }

    public function round(Request $request, Battle $battle)
    {
        // Verifica se foi enviado o user_id
        if(!$request->hasHeader('user_id') || empty($request->header('user_id')))
            return response()->json([
                'error' => true,
                'msg'   => 'header user_id not found'
            ], 400);

        // Verica se existe usuario cadastrado
        $user = User::find($request->header('user_id'));
        if(!$user)
            return response()->json([
                'error' => true,
                'msg'   => 'user not found in database'
            ], 400);

        // Verifica se o usuário é mesmo da batalha
        if($user->id !== $battle->user_id)
            return response()->json([
                'error' => true,
                'msg'   => 'user not linked with this battle'
            ], 400);

        $this->classification($battle, $user);
        dd('fim');

        if($battle->step === 'finished')
            return response()->json([
                'error' => true,
                'msg'   => 'This battle finished'
            ], 400);

        // Setando informações para calculos
        $this->hero         = $battle->hero;
        $this->monster      = $battle->monster;
        $this->battleSteps  = $battle->battleSteps;
        $this->battleId     = $battle->id;

        // Verifica qual proxima rodada
        $round = $this->verifyRound();

        if($round === 'finished'){
            $battle = $this->finished($battle);

            if(!empty($battle->classification))
                $this->classification($battle, $user);

            return response()->json([
                'error' => true,
                'msg'   => 'Finished Battle'
            ], 400);
        }

        // Executa método de regra
        $this->$round();

        // Obtém informações para salvar no banco
        $data = $this->getData();

        // Salva informações da batalha no banco
        $btStp = ModelsBattleStep::create($data);

        Battle::find($btStp->battle_id);

        // Retorna informações sobre a rodada
        return response()->json($this->responseBattle(), 200);
    }

    public function show(Battle $battle)
    {

        $battle->user       = $battle->user;
        $battle->hero       = $battle->hero;
        $battle->monster    = $battle->monster;

        return response()->json([
            'error' => false,
            'msg'   => '',
            'data'  =>  $battle
        ]);
    }

    public function history(Battle $battle)
    {
        return response()->json([
            'error' => false,
            'msg'   => '',
            'data'  =>  $battle->battleSteps
        ]);
    }

    private function finished($battle)
    {
        $lastBattle = $battle->battleSteps[count($battle->battleSteps)-1];

        $battle->step   = 'finished';
        $battle->rounds = count($battle->battleSteps()->whereRound(1)->get());

        if($lastBattle->hero_pdv === 0)
            $battle->classification = 100 - $battle->rounds;
        $battle->save();

        return $battle;
    }

    private function classification($battle, $user)
    {
        $total = 0;

        $class = Classification::where('user_id', $user->id)->first();
        if(!is_null($class))
            $total = $class->total_points;

        $classification = new Classification();
        $classification->user_id        = $user->id;
        $classification->last_battle    = $battle->id;
        $classification->total_battles  = count($user->battles);
        $classification->total_points   = $total + $battle->classification;
        $classification->save();
    }

}
