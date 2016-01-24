<?php

Route::group(['prefix' => 'access'], function() {

    Route::get('/', 'IndexController@index');
    Route::get('/users', 'IndexController@index');
    Route::get('/users/active', 'IndexController@index');
    Route::get('/users/deactivated', 'IndexController@index');
    Route::get('/user/create', 'IndexController@index');
    Route::get('/users/deleted', 'IndexController@index');
    Route::get('/user/edit/{id}', 'IndexController@index');

    Route::post('users', 'Access\UserController@index');
    Route::post('user/mark', 'Access\UserController@mark');
    Route::post('user/delete', 'Access\UserController@delete');
    Route::post('user/restore', 'Access\UserController@restore');
    Route::post('user/permanentlyDelete', 'Access\UserController@permanentlyDelete');
    Route::post('user/store', 'Access\UserController@store');

    Route::post('allRoles', 'Access\UserController@getAllRoles');

    Route::group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function() {
        Route::get('delete', 'IndexController@index');
        Route::get('restore', 'IndexController@index');
        Route::get('mark/{status}', 'IndexController@index');
        Route::get('password/change', 'IndexController@index');
        Route::post('password/change', 'IndexController@index');
    });

    Route::get('/roles', 'IndexController@index');
    Route::get('/roles/create', 'IndexController@index');

});