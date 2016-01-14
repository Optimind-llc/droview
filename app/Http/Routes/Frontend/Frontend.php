<?php

/**
 * Frontend Controllers
 */
Route::get('/', 'FrontendController@index')->name('frontend.index');
Route::get('macros', 'FrontendController@macros')->name('frontend.macros');

//API
Route::post('api/getTimetable','ApiController@getTimetable');
Route::post('api/getDefaultStatus','ApiController@getDefaultStatus');
Route::post('api/getUserInfo','ApiController@getUserInfo');
Route::post('api/updateUserProf','ApiController@updateUserProf');
Route::post('api/changePassword','ApiController@changePassword');
Route::post('api/getTestToken','ApiController@getTestToken');
Route::post('api/reserve','ApiController@reserve');
Route::post('api/rsvList','ApiController@getReservationList');
Route::post('api/cancel','ApiController@cancel');
Route::post('api/getLog','ApiController@getLog');
Route::post('api/webpay','ApiController@addByWebpay');
Route::post('api/pin','ApiController@addByPin');
Route::post('api/getAddress','ApiController@getAddress');
Route::post('api/getJwt','ApiController@getJwt');

/**
 * These frontend controllers require the user to be logged in
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User'], function() {
        Route::get('dashboard', 'DashboardController@index')->name('frontend.user.dashboard');
        Route::get('profile/edit', 'ProfileController@edit')->name('frontend.user.profile.edit');
        Route::patch('profile/update', 'ProfileController@update')->name('frontend.user.profile.update');
    });

	Route::get('mypage/reserved', 'ApiController@index');
	Route::get('mypage/reserve', 'ApiController@index')->name('frontend.mypage');
	Route::get('mypage/ticket', 'ApiController@index');
	Route::get('mypage/log', 'ApiController@index');
	Route::get('mypage/profile', 'ApiController@index');
	Route::get('mypage/todo', 'ApiController@index');
	Route::get('mypage/test', 'ApiController@test');
	Route::get('mypage/flight', 'ApiController@flight');
});