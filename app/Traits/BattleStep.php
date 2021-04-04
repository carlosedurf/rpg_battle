<?php

namespace App\Traits;

trait BattleStep{

    use Dice;

    protected $battleId;
    protected $hero;
    protected $monster;
    protected $battleSteps;
    protected $heroPdv;
    protected $monsterPdv;
    protected $valueMonster = 0;
    protected $valueHero = 0;
    protected $round = false;
    protected $valueDamage = 0;
    protected $whoAttacked;
    protected $step;

    public function initiative()
    {

        // Hero [Dado, Agilidade]
        $this->valueHero = $this->playDice() + $this->hero->agility;

        // Monster [Dado, Agilidade]
        $this->valueMonster = $this->playDice() + $this->monster->agility;

        // Configura estapa
        $this->step = 'initiative';
    }

    public function defense()
    {
        // Hero [Dado, Agilidade, Defesa]
        $this->valueHero = $this->playDice() + $this->hero->agility + $this->hero->defense;

        // Monster [Dado, Agilidade, Força]
        $this->valueMonster = $this->playDice() + $this->monster->agility + $this->monster->force;

        // Configura estapa
        $this->step = 'defense';
        $this->round = true;

        if($this->valueHero < $this->valueMonster){
            $this->whoAttacked = 'monster';
        }

    }

    public function attack()
    {
        // Hero [Dado, Agilidade, Força]
        $this->valueHero = $this->playDice() + $this->hero->agility + $this->hero->force;

        // Monster [Dado, Agilidade, Defesa]
        $this->valueMonster = $this->playDice() + $this->monster->agility + $this->monster->defense;

        // Configura estapa
        $this->step = 'attack';
        $this->round = true;

        if($this->valueHero > $this->valueMonster){
            $this->whoAttacked = 'hero';
        }

    }

    public function damage()
    {
        if($this->whoAttacked === 'hero'){

            // Hero [PdD + Força]
            $this->valueHero = $this->playDice($this->hero->fdd) + $this->hero->force;
            $this->valueDamage = $this->valueHero;

            // Retira PdV do Monster
            $this->monsterPdv -= $this->valueDamage;

            if($this->monsterPdv < 0)
                $this->monsterPdv = 0;

        }elseif($this->whoAttacked === 'monster'){

            // Monster [PdD + Força]
            $this->valueMonster = $this->playDice($this->monster->fdd) + $this->monster->force;
            $this->valueDamage = $this->valueMonster;

            // Retira PdV do Hero
            $this->heroPdv -= $this->valueDamage;

            if($this->heroPdv < 0)
                $this->heroPdv = 0;

        }

        $this->step = 'damage';
    }

    public function verifyRound()
    {
        $this->round = 0;
        $this->whoAttacked = '';
        $countBattleSteps = count($this->battleSteps);

        if($countBattleSteps == 0){
            $this->heroPdv      = $this->hero->pdv;
            $this->monsterPdv   = $this->monster->pdv;

            return 'initiative';
        }else{
            // Pegando ultima rodada da batalha
            $this->battleSteps = $this->battleSteps[$countBattleSteps-1];

            $this->heroPdv      = $this->battleSteps->hero_pdv;
            $this->monsterPdv   = $this->battleSteps->monster_pdv;

            if($this->battleSteps->step === 'initiative'){

                if($this->battleSteps->value_hero === $this->battleSteps->value_monster)
                    return 'initiative';
                elseif($this->battleSteps->value_hero > $this->battleSteps->value_monster)
                    return 'attack';
                elseif($this->battleSteps->value_hero < $this->battleSteps->value_monster)
                    return 'defense';

            }elseif($this->battleSteps->step === 'attack' || $this->battleSteps->step === 'defense'){

                if(!empty($this->battleSteps->who_attack)){
                    $this->whoAttacked = $this->battleSteps->who_attack;
                    return 'damage';
                }else{
                    return 'initiative';
                }

            }elseif($this->battleSteps->step === 'damage'){

                if($this->battleSteps->monster_pdv == 0 || $this->battleSteps->hero_pdv == 0)
                    return 'finished';
                else
                    return 'initiative';
            }

        }

    }

    public function getData()
    {
        return [
            'battle_id'     =>  $this->battleId,
            'step'          =>  $this->step,
            'hero_pdv'      =>  $this->heroPdv,
            'monster_pdv'   =>  $this->monsterPdv,
            'value_hero'    =>  $this->valueHero,
            'value_monster' =>  $this->valueMonster,
            'round'         =>  $this->round,
            'who_attack'    =>  $this->whoAttacked
        ];
    }

    public function responseBattle()
    {

        switch($this->step){

            case 'initiative':
                if($this->valueHero === $this->valueMonster)
                    return [
                        'tied'          =>  true,
                        'hero'          =>  $this->hero->class,
                        'hero_pdv'      =>  $this->heroPdv,
                        'monster'       =>  $this->monster->class,
                        'monster_pdv'   =>  $this->monsterPdv
                    ];
                elseif($this->valueHero > $this->valueMonster)
                    return [
                        'tied'          =>  false,
                        'attacker'      =>  $this->hero->class,
                        'defender'      =>  $this->monster->class,
                        'hero'          =>  $this->hero->class,
                        'hero_pdv'      =>  $this->heroPdv,
                        'monster'       =>  $this->monster->class,
                        'monster_pdv'   =>  $this->monsterPdv
                    ];
                elseif($this->valueHero < $this->valueMonster)
                    return [
                        'tied'          =>  false,
                        'attacker'      =>  $this->monster->class,
                        'defender'      =>  $this->hero->class,
                        'hero'          =>  $this->hero->class,
                        'hero_pdv'      =>  $this->heroPdv,
                        'monster'       =>  $this->monster->class,
                        'monster_pdv'   =>  $this->monsterPdv
                    ];
            break;

            case 'attack':
                return [
                    'tied'          =>  false,
                    'situation'     =>  'Defended by ' . $this->monster->class,
                    'hero'          =>  $this->hero->class,
                    'hero_pdv'      =>  $this->heroPdv,
                    'monster'       =>  $this->monster->class,
                    'monster_pdv'   =>  $this->monsterPdv
                ];
            break;

            case 'defense':
                return [
                    'tied'          =>  false,
                    'situation'     =>  'Defended by ' . $this->hero->class,
                    'hero'          =>  $this->hero->class,
                    'hero_pdv'      =>  $this->heroPdv,
                    'monster'       =>  $this->monster->class,
                    'monster_pdv'   =>  $this->monsterPdv
                ];
            break;

            case 'damage':
                return [
                    'tied'          =>  false,
                    'situation'     =>  'Attack in ' . $this->whoAttacked . ' with ' . $this->valueDamage,
                    'total_damage'  =>  $this->valueDamage,
                    'hero'          =>  $this->hero->class,
                    'hero_pdv'      =>  $this->heroPdv,
                    'monster'       =>  $this->monster->class,
                    'monster_pdv'   =>  $this->monsterPdv
                ];
            break;

        }

    }

}
