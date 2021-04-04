<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Seeder;

class HeroSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hero::create([
            'class'     =>  'Guerreiro',
            'pdv'       =>  12,
            'force'     =>  4,
            'defense'   =>  3,
            'agility'   =>  3,
            'fdd'       =>  '2d4'
        ]);

        Hero::create([
            'class'     =>  'Bárbaro',
            'pdv'       =>  13,
            'force'     =>  6,
            'defense'   =>  1,
            'agility'   =>  3,
            'fdd'       =>  '2d6'
        ]);

        Hero::create([
            'class'     =>  'Paladino',
            'pdv'       =>  15,
            'force'     =>  2,
            'defense'   =>  5,
            'agility'   =>  1,
            'fdd'       =>  '2d4'
        ]);
    }
}
