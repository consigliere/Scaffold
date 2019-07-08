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
 * Last modified 7/9/19 2:52 AM
 */

Route::group(['prefix' => 'v1'], static function() {
    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'users',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/profile', 'UserController@profile')->middleware('http.accept');
            Route::get('/', 'UserController@browse')->middleware('http.accept', 'scopes:browse_users');
            Route::get('/{uuid}', 'UserController@read')->middleware('http.accept', 'scopes:read_users');
            Route::post('/', 'UserController@create')->middleware('http.accept', 'http.content-type', 'scopes:add_users');
            Route::patch('/{uuid}', 'UserController@update')->middleware('http.accept', 'http.content-type', 'scopes:edit_users');
            Route::delete('/{uuid}', 'UserController@delete')->middleware('http.accept', 'scopes:delete_users');

            Route::get('/{uuid}/relationships/primary-role', 'UserController@relatedPrimaryRole')->middleware('http.accept', 'scopes:read_users');
            Route::get('/{uuid}/primary-role', 'UserController@primaryRole')->middleware('http.accept', 'scopes:read_users');
            Route::get('/{uuid}/relationships/additional-roles', 'UserController@relatedAdditionalRoles')->middleware('http.accept', 'scopes:read_users');
            Route::get('/{uuid}/additional-roles', 'UserController@additionalRoles')->middleware('http.accept', 'scopes:read_users');
            // Route::patch('/{uuid}/relationships/additional-roles/{type}', 'UserController@userAdditionalRoles'); # type = sync, add or remove
            Route::patch('/{uuid}/relationships/sync-additional-roles', 'UserController@syncAdditionalRoles')->middleware('http.accept', 'http.content-type', 'scope:add_users,delete_users');
            Route::patch('/{uuid}/relationships/add-additional-roles', 'UserController@addAdditionalRoles')->middleware('http.accept', 'http.content-type', 'scopes:add_users');
            Route::patch('/{uuid}/relationships/remove-additional-roles', 'UserController@removeAdditionalRoles')->middleware('http.accept', 'http.content-type', 'scopes:delete_users');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'roles',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/', 'RoleController@browse')->middleware('http.accept', 'scopes:browse_roles');
            Route::get('/{uuid}', 'RoleController@read')->middleware('http.accept', 'scopes:read_roles');
            Route::post('/', 'RoleController@create')->middleware('http.accept', 'http.content-type', 'scopes:add_roles');
            Route::patch('/{uuid}', 'RoleController@update')->middleware('http.accept', 'http.content-type', 'scopes:edit_roles');
            Route::delete('/{uuid}', 'RoleController@delete')->middleware('http.accept', 'scopes:delete_roles');

            Route::get('/{uuid}/relationships/permissions', 'RoleController@relatedPermissions')->middleware('http.accept', 'scopes:read_roles');
            Route::get('/{uuid}/permissions', 'RoleController@permissions')->middleware('http.accept', 'scopes:read_roles');
            Route::patch('/{uuid}/relationships/sync-permissions', 'RoleController@syncPermissions')->middleware('http.accept', 'http.content-type', 'scope:add_roles,delete_roles');
            Route::patch('/{uuid}/relationships/add-permissions', 'RoleController@addPermissions')->middleware('http.accept', 'http.content-type', 'scopes:add_roles');
            Route::patch('/{uuid}/relationships/remove-permissions', 'RoleController@removePermissions')->middleware('http.accept', 'http.content-type', 'scopes:delete_roles');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'permissions',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/', 'PermissionController@browse')->middleware('http.accept');
            Route::get('/{uuid}', 'PermissionController@read')->middleware('http.accept');
            Route::post('/', 'PermissionController@create')->middleware('http.accept', 'http.content-type');
            Route::patch('/{uuid}', 'PermissionController@update')->middleware('http.accept', 'http.content-type');
            Route::delete('/{uuid}', 'PermissionController@delete')->middleware('http.accept');
        }
    );
});