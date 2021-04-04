<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'step',
        'hero_pdv',
        'monster_pdv',
        'value_hero',
        'value_monster',
        'damage',
        'round',
        'battle_id',
        'who_attack'
    ];

    public function battle()
    {
        return $this->belongsTo(Battle::class);
    }

}
