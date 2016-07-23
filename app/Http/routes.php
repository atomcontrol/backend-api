<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * API ROUTES
 */
Route::group(['prefix' => 'v1','namespace'=>'API'], function()
{
    //splash page signups
    Route::post('auth', 'AuthController@login');
    Route::post('users', 'AuthController@signUp');
    Route::get('debug', 'AuthController@debug');
    
    Route::group(array('prefix' => 'users/me'), function() {
        Route::get('/', 'UsersController@getMe');
        Route::put('/', 'UsersController@updateMe');
    });

    Route::group(array('prefix' => 'recipes'), function() {
        Route::get('/', 'RecipeController@index');
        Route::get('/{slug}', 'RecipeController@show');
        Route::put('/{slug}', 'RecipeController@update');
    });
});
