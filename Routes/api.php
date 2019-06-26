<?php
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

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/26/19 7:00 AM
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

            Route::get('/{uuid}/relationships/primary-role', 'UserController@relatedPrimaryRole');
            Route::get('/{uuid}/relationships/additional-roles', 'UserController@relatedAdditionalRoles');
            Route::get('/{uuid}/roles', 'UserController@roles');
            Route::get('/{uuid}/primary-role', 'UserController@primaryRole');
            Route::get('/{uuid}/additional-roles', 'UserController@additionalRoles');
            // Route::patch('/{uuid}/relationships/additional-roles/{type}', 'UserController@additionalRoles'); # type = sync, add or remove
            Route::patch('/{uuid}/relationships/additional-roles/sync', 'UserController@syncAdditionalRoles');
            Route::patch('/{uuid}/relationships/additional-roles/add', 'UserController@addAdditionalRoles');
            Route::patch('/{uuid}/relationships/additional-roles/remove', 'UserController@removeAdditionalRoles');
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

            Route::patch('/{uuid}/relationships/permissions', 'RoleController@assignPermission');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'permissions',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        function() {
            Route::get('/', 'PermissionController@browse');
            Route::get('/{uuid}', 'PermissionController@read');
            Route::post('/', 'PermissionController@create');
            Route::patch('/{uuid}', 'PermissionController@update');
            Route::delete('/{uuid}', 'PermissionController@delete');
        }
    );
});