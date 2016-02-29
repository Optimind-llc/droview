<?php

/**
 * Frontend Flights
 */
Route::group(['middleware' => 'auth'], function () {
	Route::get('mypage/reserve', 'ApiController@index')->name('frontend.mypage');
	Route::get('mypage/timetable/{id}', 'ApiController@index');

	Route::post('mypage/timetables', 'FlightController@timetables');
	Route::post('mypage/confirmReservation', 'FlightController@confirmReservation');
	Route::post('mypage/reserve', 'FlightController@reserve');

	Route::get('mypage/dbtest', 'FlightController@dbtest');

	Route::post('connectionTest', 'ConnectionController@connectionTest');
});