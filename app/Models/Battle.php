<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hero_id',
        'monster_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    public function monster()
    {
        return $this->belongsTo(Monster::class);
    }

    public function battleSteps()
    {
        return $this->hasMany(BattleStep::class);
    }

}
