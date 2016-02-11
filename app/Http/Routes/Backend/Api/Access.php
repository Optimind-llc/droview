<?php

Route::group(['prefix' => 'access'], function() {
    Route::get('', 'IndexController@index');

    Route::get('users', 'IndexController@index');
    Route::get('user/create', 'IndexController@index');
    Route::get('user/{id}/edit', 'IndexController@index');
    Route::get('user/{id}/password/change', 'IndexController@index');

    Route::group(['namespace'  => 'Access'], function() {
        Route::get('users/fetch', 'UserController@index');
        Route::get('user/{id}/fetch', 'UserController@show');
        Route::post('user', 'UserController@store');
        Route::put('user/{id}', 'UserController@update');
        Route::patch('user/{id}/activate', 'UserController@activate');
        Route::patch('user/{id}/deactivate', 'UserController@deactivate');
        Route::patch('user/{id}/restore', 'UserController@restore');
        Route::delete('user/{id}', 'UserController@destroy');
        Route::delete('user/{id}/hard', 'UserController@delete');
        Route::post('user/{id}/password/change', 'UserController@updatePassword');
        Route::get('user/{id}/confirm/resend', 'UserController@resendConfirmationEmail');
    });

    Route::get('roles', 'IndexController@index');
    Route::get('roles/create', 'IndexController@index');
    Route::get('roles/{id}/edit', 'IndexController@index');

    Route::get('roles/fetch', 'Access\RoleController@index');
    Route::get('roles/{id}/fetch', 'Access\RoleController@show');
    Route::post('roles', 'Access\RoleController@store');
    Route::put('roles/{id}', 'Access\RoleController@update');
    Route::delete('roles/{id}', 'Access\RoleController@destroy');

    Route::get('permissions', 'IndexController@index');

    Route::get('permissions/fetch', 'Access\PermissionController@index');
    Route::get('permissions/{id}/dependency', 'Access\PermissionController@permissionDependency');
});
