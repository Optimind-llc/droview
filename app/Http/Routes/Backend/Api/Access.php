<?php

Route::group(['prefix' => 'access'], function() {

    Route::get('', 'IndexController@index');
    Route::get('users', 'IndexController@index');
    Route::get('users/active', 'IndexController@index');
    Route::get('users/deactivated', 'IndexController@index');
    Route::get('user/create', 'IndexController@index');
    Route::get('users/deleted', 'IndexController@index');
    Route::get('user/edit/{id}', 'IndexController@index');
    Route::get('user/change/password/{id}', 'IndexController@index');

    Route::post('users', 'Access\UserController@users');
    Route::post('user', 'Access\UserController@user');
    Route::post('user/mark', 'Access\UserController@mark');
    Route::post('user/delete', 'Access\UserController@delete');
    Route::post('user/restore', 'Access\UserController@restore');
    Route::post('user/permanentlyDelete', 'Access\UserController@permanentlyDelete');
    Route::post('user/store', 'Access\UserController@store');
    Route::post('user/change/password', 'Access\UserController@updatePassword');
    Route::post('user/update', 'Access\UserController@update');


    Route::get('roles', 'IndexController@index');
    Route::get('role/create', 'IndexController@index');
    Route::get('role/edit/{id}', 'IndexController@index');

    Route::post('roles', 'Access\RoleController@index');
    Route::post('role/delete', 'Access\RoleController@destroy');

    Route::get('permissions', 'IndexController@index');

    Route::post('permissions', 'Access\PermissionController@index');

});