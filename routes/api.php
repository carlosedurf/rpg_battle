<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BattleController,
    ClassificationController,
    UserController,
    HeroController,
    MonsterController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/users')->name('users.')->group(function (){

    Route::get('{user}', [UserController::class, 'show'])->name('show');
    Route::get('{user}/battles', [UserController::class, 'battles'])->name('battles');
    Route::post('create', [UserController::class, 'store'])->name('create');

});


Route::prefix('/battles')->name('battles.')->group(function (){

    Route::post('{battle}/round', [BattleController::class, 'round'])->name('round');
    Route::get('{battle}', [BattleController::class, 'show'])->name('show');
    Route::get('{battle}/history', [BattleController::class, 'history'])->name('history');

    Route::post('start', [BattleController::class, 'start'])->name('start');

});

Route::get('/classifications', [ClassificationController::class, 'index'])->name('classifications.index');

Route::get('/heroes', [HeroController::class, 'index'])->name('heroes.index');
Route::get('/monster', [MonsterController::class, 'index'])->name('monsters.index');
