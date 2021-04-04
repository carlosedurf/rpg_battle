<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monster;

class MonsterSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Monster::create([
            'class'     =>  'Morto-Vivo',
            'pdv'       =>  25,
            'force'     =>  4,
            'defense'   =>  0,
            'agility'   =>  1,
            'fdd'       =>  '2d4'
        ]);

        Monster::create([
            'class'     =>  'Orc',
            'pdv'       =>  20,
            'force'     =>  6,
            'defense'   =>  2,
            'agility'   =>  2,
            'fdd'       =>  '1d8'
        ]);

        Monster::create([
            'class'     =>  'Kobold',
            'pdv'       =>  20,
            'force'     =>  4,
            'defense'   =>  2,
            'agility'   =>  4,
            'fdd'       =>  '3d2'
        ]);
    }
}
