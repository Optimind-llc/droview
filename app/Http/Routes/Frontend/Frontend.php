<?php

//要リファクタリング
Route::get('admin/single/api/getUserInfo','ApiController@getUserInfo');

//API
Route::post('api/getTimetable','ApiController@getTimetable');
Route::post('api/getDefaultStatus','ApiController@getDefaultStatus');
Route::post('api/getUserInfo','ApiController@getUserInfo');
Route::post('api/updateUserProf','ApiController@updateUserProf');
Route::post('api/changePassword','ApiController@changePassword');
Route::post('api/rsvList','ApiController@getReservationList');
Route::post('api/cancel','ApiController@cancel');
Route::post('api/getLog','ApiController@getLog');
Route::post('api/webpay','ApiController@addByWebpay');
Route::post('api/pin','ApiController@addByPin');
Route::post('api/getAddress','ApiController@getAddress');

/**
 * These frontend controllers require the user to be logged in
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User'], function() {
        Route::get('dashboard', 'DashboardController@index')->name('frontend.user.dashboard');
        Route::get('profile/edit', 'ProfileController@edit')->name('frontend.user.profile.edit');
        Route::patch('profile/update', 'ProfileController@update')->name('frontend.user.profile.update');
    });
	Route::delete('auth', 'Auth\AuthController@destroy');
});