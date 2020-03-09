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
    Route::get('user/player/search/{search}', 'User\Actor\PlayerController@search');
    Route::resource('user', 'User\UserController', [
        'except' => ['create', 'edit', 'show']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/
    // Profile
    Route::get('profile', 'Profile\ProfileController@index');
    /* -------------------------------------------------------------------------------------------------------*/
    // Game
    Route::resource('game', 'Game\GameController', [
        'except' => ['create', 'edit']
    ]);
    // Game / Ranking
    Route::get('game/{game}/ranking', 'Game\GameController@ranking');
    // Game / Medal
    Route::resource('game.medal', 'Game\Medal\MedalController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Score
    Route::resource('game.score', 'Game\Score\ScoreController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Phase
    Route::resource('game.phase', 'Game\Phase\PhaseController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Player
    Route::resource('game.player', 'Game\Player\PlayerController', [
        'except' => ['create', 'edit']
    ]);
    // Game / Score
    Route::resource('game.score', 'Game\Score\ScoreController', [
        'except' => ['create', 'edit', 'show']
    ]);
    // Game / Player / Medal
    Route::resource('game.player.medal', 'Game\Player\Medal\MedalController', [
        'except' => ['create', 'edit', 'update', 'show']
    ]);
    // Game / Player / Score
    Route::resource('game.player.score', 'Game\Player\Score\ScoreController', [
        'except' => ['create', 'edit', 'show']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/
    // Medal
    Route::get('medal/search/{search}', 'Medal\MedalController@search');
    Route::resource('medal', 'Medal\MedalController', [
        'except' => ['create', 'edit']
    ]);
    /* -------------------------------------------------------------------------------------------------------*/
    // Player
    Route::resource('player', 'Player\PlayerController', [
        'only' => ['index', 'show']
    ]);
});
