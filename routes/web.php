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

// Test
Route::get('/test', 'Test\TestController@index')->name('test');

// Rotas protegidas por autenticação
Route::group(['middleware' => ['auth']], function () {
    // Todos os usuários com paginação.
    Route::get('user', 'Administration\User\UserController@index')
        ->name('user.index');
    Route::put('user/{user}/actor/{actor}', 'Administration\User\Actor\ActorController@update')
        ->name('user.actor.update');
});
