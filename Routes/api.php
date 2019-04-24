<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/22/19 1:14 PM
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

//Route::middleware('auth:api')->get('/scaffold', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function() {
    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'user',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        function() {
            // Route::get('/profile', 'UserController@profile');
            Route::post('/', 'UserController@create');
            Route::put('/{id}', 'UserController@update');
        }
    );
});