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
    Route::get('networkdevicestest', 'GeneralController@networkdevicestest');
    Route::get('test', 'GeneralController@test');

    Route::group(array('prefix' => 'users/me'), function() {
        Route::get('/', 'UsersController@getMe');
        Route::put('/', 'UsersController@updateMe');
    });

    Route::group(array('prefix' => 'recipes'), function() {
        Route::get('/', 'RecipeController@index');
        Route::get('ingredients', 'RecipeController@ingredientsIndex');
        Route::get('/{slug}', 'RecipeController@show');
        Route::put('/{slug}', 'RecipeController@update');
        Route::post('/{slug}', 'RecipeController@update');
    });

    Route::group(['prefix' => 'meals'], function() {
        Route::get('/', 'MealController@index');
        Route::get('/shoppinglist', 'MealController@shoppingList');
    });

    Route::group(['prefix' => 'lights'], function() {
        Route::get('/', 'LightingController@index');
        Route::get('/test', 'LightingController@test');
    });

    Route::group(['prefix' => 'system'], function() {
        Route::get('network/devices', 'SystemController@networkDevices');
        Route::get('network/speed', 'SystemController@networkSpeed');
    });

    Route::group(['prefix' => 'data/collect'], function() {
        Route::put('speedtest', 'DataCollector@receiveSpeedTestData');
        Route::put('networkscan', 'DataCollector@receiveNetworkScan');
        Route::put('dashbutton', 'DataCollector@receiveDashButtonClick');
    });
});
