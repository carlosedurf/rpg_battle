<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->constrained()->cascadeOnDelete();

            $table->enum('step', [
                'initiative',
                'defense',
                'attack',
                'damage',
            ])->comment('Descrição do Registro gravado');

            $table->integer('hero_pdv')->comment('Pontos de Vida do Herói na rodada atual');
            $table->integer('monster_pdv')->comment('Pontos de Vida do Monstro na rodada atual');

            $table->integer('value_hero')->default(0)->comment('Valor calculado para o Herói na rodada atual');
            $table->integer('value_monster')->default(0)->comment('Valor calculado para o Monstro na rodada atual');

            $table->integer('damage')->default(0)->comment('Valor de dano calculo de acordo com a regra');

            $table->boolean('round')->default(0)->comment('Marcado com fechamento da rodada');

            $table->string('who_attack')->nullable()->comment('Quem vai calcular o attack');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battle_steps');
    }
}
