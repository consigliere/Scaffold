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
 * Last modified 7/9/19 12:53 AM
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
            Route::get('/', 'UserController@browse')->middleware('scopes:browse_users');
            Route::get('/{uuid}', 'UserController@read')->middleware('scopes:read_users');
            Route::post('/', 'UserController@create')->middleware('scopes:add_users');
            Route::patch('/{uuid}', 'UserController@update')->middleware('scopes:edit_users');
            Route::delete('/{uuid}', 'UserController@delete')->middleware('scopes:delete_users');

            Route::get('/{uuid}/relationships/primary-role', 'UserController@relatedPrimaryRole')->middleware('scopes:read_users');
            Route::get('/{uuid}/primary-role', 'UserController@primaryRole')->middleware('scopes:read_users');
            Route::get('/{uuid}/relationships/additional-roles', 'UserController@relatedAdditionalRoles')->middleware('scopes:read_users');
            Route::get('/{uuid}/additional-roles', 'UserController@additionalRoles')->middleware('scopes:read_users');
            // Route::patch('/{uuid}/relationships/additional-roles/{type}', 'UserController@userAdditionalRoles'); # type = sync, add or remove
            Route::patch('/{uuid}/relationships/sync-additional-roles', 'UserController@syncAdditionalRoles')->middleware('scope:add_users,delete_users');
            Route::patch('/{uuid}/relationships/add-additional-roles', 'UserController@addAdditionalRoles')->middleware('scopes:add_users');
            Route::patch('/{uuid}/relationships/remove-additional-roles', 'UserController@removeAdditionalRoles')->middleware('scopes:delete_users');
        }
    );

    Route::group(
        [
            'middleware' => 'auth:api',
            'prefix'     => 'roles',
            'namespace'  => '\App\Components\Scaffold\Http\Controllers',
        ],
        static function() {
            Route::get('/', 'RoleController@browse')->middleware('scopes:browse_roles');
            Route::get('/{uuid}', 'RoleController@read')->middleware('scopes:read_roles');
            Route::post('/', 'RoleController@create')->middleware('scopes:add_roles');
            Route::patch('/{uuid}', 'RoleController@update')->middleware('scopes:edit_roles');
            Route::delete('/{uuid}', 'RoleController@delete')->middleware('scopes:delete_roles');

            Route::get('/{uuid}/relationships/permissions', 'RoleController@relatedPermissions')->middleware('scopes:read_roles');
            Route::get('/{uuid}/permissions', 'RoleController@permissions')->middleware('scopes:read_roles');
            Route::patch('/{uuid}/relationships/sync-permissions', 'RoleController@syncPermissions')->middleware('scope:add_roles,delete_roles');
            Route::patch('/{uuid}/relationships/add-permissions', 'RoleController@addPermissions')->middleware('scopes:add_roles');
            Route::patch('/{uuid}/relationships/remove-permissions', 'RoleController@removePermissions')->middleware('scopes:delete_roles');
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