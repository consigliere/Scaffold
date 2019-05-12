<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 8:36 AM
 */

/**
 * api.php
 * Created by @anonymoussc on 03/11/2019 7:31 PM.
 */

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

Route::group(['prefix' => 'v1'], function() {
    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'users',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        function() {
            Route::get('/profile', 'UserController@profile');
            Route::get('/', 'UserController@browse');
            Route::get('/{uuid}', 'UserController@read');
            Route::post('/', 'UserController@create');
            Route::patch('/{uuid}', 'UserController@update');
            Route::delete('/{uuid}', 'UserController@delete');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'roles',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        function() {
            Route::get('/', 'RoleController@browse');
            Route::get('/{uuid}', 'RoleController@read');
            Route::post('/', 'RoleController@create');
            Route::patch('/{uuid}', 'RoleController@update');
            Route::delete('/{uuid}', 'RoleController@delete');
        }
    );
});