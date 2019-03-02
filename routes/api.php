<?php

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

Route::group(['middleware' => 'auth:api'], function () {
    /* -------------------------------------------------------------------------------------------------------*/
    // User
    Route::resource('user', 'User\UserController', [
        'except' => ['create', 'edit', 'show']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/
    // Game
    Route::resource('game', 'Game\GameController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Phase
    Route::resource('game.phase', 'Game\Phase\PhaseController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Player
    Route::resource('game.player', 'Game\Player\PlayerController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Player / Medal
    Route::resource('game.player.medal', 'Game\Player\Medal\MedalController', [
        'except' => ['create', 'edit', 'update', 'show']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/
    // Medal
    Route::resource('medal', 'Medal\MedalController', [
        'except' => ['create', 'edit', 'show']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/

    // Acima, OK!!!

    // Game / Player / Score
    Route::resource('game.player.score', 'Game\Player\Score\ScoreController', [
        'except' => ['create', 'edit', 'update', 'show']
    ]);
});