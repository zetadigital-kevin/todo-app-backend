<?php

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

Route::group(['prefix' => 'administrator'], function () {
    Route::post('login', 'Api\Core\AdministratorApiController@administratorLoginApi');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Api\Core\AdministratorApiController@administratorLogoutApi');
        Route::get('get', 'Api\Core\AdministratorApiController@getLoggedInAdministratorApi');
        Route::get('permissions/list', 'Api\Core\AdministratorApiController@getLoggedInAdministratorPermissionsApi');
        Route::get('list', 'Api\Core\AdministratorApiController@getAdministratorsApi');
        Route::get('get/{administratorId}', 'Api\Core\AdministratorApiController@getAdministratorApi');
        Route::post('create', 'Api\Core\AdministratorApiController@createAdministratorApi');
        Route::put('update/{administratorId}', 'Api\Core\AdministratorApiController@updateAdministratorApi');
        Route::put('password/update', 'Api\Core\AdministratorApiController@updateLoggedInAdministratorPasswordApi');
        Route::put('activated/update/{administratorId}', 'Api\Core\AdministratorApiController@updateAdministratorActivatedStatusApi');
        Route::put('update', 'Api\Core\AdministratorApiController@updateLoggedInAdministratorApi');
        Route::delete('delete/{administratorId}', 'Api\Core\AdministratorApiController@deleteAdministratorApi');
    });
});

Route::group(['prefix' => 'role'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('list', 'Api\Core\RolePermissionApiController@getRolesApi');
        Route::get('get/{roleId}', 'Api\Core\RolePermissionApiController@getRoleApi');
        Route::get('permissions/list/{roleId}', 'Api\Core\RolePermissionApiController@getRolePermissionsApi');
        Route::get('administrator/list/{roleId}', 'Api\Core\RolePermissionApiController@getRoleUsersApi');
        Route::post('create', 'Api\Core\RolePermissionApiController@createRoleApi');
        Route::put('permissions/update/{roleId}', 'Api\Core\RolePermissionApiController@updateRolePermissionsApi');
        Route::put('update/{roleId}', 'Api\Core\RolePermissionApiController@updateRoleApi');
        Route::delete('delete/{roleId}', 'Api\Core\RolePermissionApiController@deleteRoleApi');
    });
});

Route::group(['prefix' => 'permission'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('list', 'Api\Core\RolePermissionApiController@getPermissionsApi');
    });
});

Route::group(['prefix' => 'setting'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('{category}/get/{scope}', 'Api\Core\SettingApiController@getSettingsApi');
        Route::put('{category}/update/{scope}', 'Api\Core\SettingApiController@updateSettingsApi');
    });
});

Route::group(['prefix' => 'task'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('create', 'Api\TaskApiController@createTaskApi');
        Route::post('share', 'Api\TaskApiController@shareTaskApi');
        Route::get('list', 'Api\TaskApiController@getTasksApi');
        Route::get('list/self', 'Api\TaskApiController@getSelfTasksApi');
        Route::get('get/{taskId}', 'Api\TaskApiController@getTaskApi');
        Route::get('get/share/{sharedTaskId}', 'Api\TaskApiController@getSharedTaskApi');
        Route::put('update/{taskId}', 'Api\TaskApiController@updateTaskApi');
        Route::delete('delete/{taskId}', 'Api\TaskApiController@deleteTaskApi');
        Route::delete('shareTask/delete/{sharedTaskId}', 'Api\TaskApiController@deleteShareTaskApi');
    });
});
