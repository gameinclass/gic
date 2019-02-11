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
    // User
    Route::get('user', 'User\UserController@index');
    Route::post('user', 'User\UserController@store');
    Route::put('user/{user}', 'User\UserController@update')->where('user', '[0-9]+');
    Route::delete('user/{user}', 'User\UserController@destroy')->where('user', '[0-9]+');
    // Game
    Route::get('game', 'Game\GameController@index');
    Route::post('game', 'Game\GameController@store');
    Route::put('game/{game}', 'Game\GameController@update')->where('game', '[0-9]+');
    Route::delete('game/{game}', 'Game\GameController@destroy')->where('game', '[0-9]+');
    // Medal
    Route::get('medal', 'Medal\MedalController@index');
    Route::post('medal', 'Medal\MedalController@store');
    Route::put('medal/{medal}', 'Medal\MedalController@update')->where('medal', '[0-9]+');
    Route::delete('medal/{medal}', 'Medal\MedalController@destroy')->where('medal', '[0-9]+');
});