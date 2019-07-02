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
 * Last modified 7/2/19 7:23 AM
 */

Route::group(['prefix' => 'v1'], static function() {
    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'users',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/profile', 'UserController@profile');
            Route::get('/', 'UserController@browse');
            Route::get('/{uuid}', 'UserController@read');
            Route::post('/', 'UserController@create');
            Route::patch('/{uuid}', 'UserController@update');
            Route::delete('/{uuid}', 'UserController@delete');

            Route::get('/{uuid}/relationships/primary-role', 'UserController@relatedPrimaryRole');
            Route::get('/{uuid}/primary-role', 'UserController@primaryRole');
            Route::get('/{uuid}/relationships/additional-roles', 'UserController@relatedAdditionalRoles');
            Route::get('/{uuid}/additional-roles', 'UserController@additionalRoles');
            // Route::patch('/{uuid}/relationships/additional-roles/{type}', 'UserController@userAdditionalRoles'); # type = sync, add or remove
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
        static function() {
            Route::get('/', 'RoleController@browse');
            Route::get('/{uuid}', 'RoleController@read');
            Route::post('/', 'RoleController@create');
            Route::patch('/{uuid}', 'RoleController@update');
            Route::delete('/{uuid}', 'RoleController@delete');

            Route::get('/{uuid}/relationships/permissions', 'RoleController@relatedPermissions');
            Route::get('/{uuid}/permissions', 'RoleController@permissions');
            Route::patch('/{uuid}/relationships/permissions/sync', 'RoleController@syncPermissions');
            Route::patch('/{uuid}/relationships/permissions/add', 'RoleController@addPermissions');
            Route::patch('/{uuid}/relationships/permissions/remove', 'RoleController@removePermissions');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'permissions',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/', 'PermissionController@browse');
            Route::get('/{uuid}', 'PermissionController@read');
            Route::post('/', 'PermissionController@create');
            Route::patch('/{uuid}', 'PermissionController@update');
            Route::delete('/{uuid}', 'PermissionController@delete');
        }
    );
});