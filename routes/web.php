<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::view('profile', 'profile')->name('profile');

    Route::get('user', 'Administration\User\UserController@index')
        ->name('user.index');

    Route::put('user/{user}/actor/{actor}', 'Administration\User\Actor\ActorController@update')
        ->name('user.actor.update');

    Route::resource('apps', 'Authorization\AuthorizationController', [
        'only' => ['index']
    ]);

    Route::resource('player', 'Player\PlayerController');
    Route::resource('player.game', 'Player\Game\GameController');

    Route::get('game/{game}/ranking', 'Game\GameController@ranking');
});
