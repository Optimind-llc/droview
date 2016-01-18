<?php

Route::group(['prefix' => 'access'], function() {

    Route::get('/', 'IndexController@index');
    Route::get('/users', 'IndexController@index');
    Route::get('/user/create', 'IndexController@index');
    Route::get('/users/deactivated', 'IndexController@index');
    Route::get('/users/deleted', 'IndexController@index');

    Route::post('users', 'Access\UserController@index');

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