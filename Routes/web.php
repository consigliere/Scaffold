<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 3/11/19 7:21 PM
 */

/**
 * web.php
 * Created by @anonymoussc on 03/11/2019 7:31 PM.
 */

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

Route::prefix('scaffold')->group(function() {
    Route::get('/', 'ScaffoldController@index');
});
