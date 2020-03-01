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

    Route::get('user', 'Administration\User\UserController@index')
        ->name('user.index');

    Route::put('user/{user}/actor/{actor}', 'Administration\User\Actor\ActorController@update')
        ->name('user.actor.update');

    Route::resource('apps', 'Authorization\AuthorizationController', [
        'only' => ['index']
    ]);

});

Route::get('r', function () {
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="routes.csv"');

    $routes = Route::getRoutes();
    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['METHOD', 'URI', 'NAME', 'ACTION']);
    foreach ($routes as $route) {
        fputcsv($fp, [head($route->methods()), $route->uri(), $route->getName(), $route->getActionName()]);
    }
    fclose($fp);
});
