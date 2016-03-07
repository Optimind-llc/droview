<?php

/**
 * Frontend Flights
 */
Route::group(['middleware' => 'auth'], function () {
	Route::get('mypage/reserve', 'ApiController@index')->name('frontend.mypage');
	Route::get('mypage/timetable/{id}', 'ApiController@index');
	Route::get('mypage/log', 'ApiController@index');
	Route::get('mypage/log/ticket', 'ApiController@index');
	Route::get('mypage/log/reservation', 'ApiController@index');

	Route::get('mypage/plans/fetch', 'PlanController@plans');
	Route::get('mypage/plan/{id}/fetch', 'PlanController@plan');

	Route::get('mypage/log/ticket/fetch', 'LogController@ticket');
	Route::get('mypage/log/reservation/fetch', 'LogController@reservation');

	Route::post('mypage/timetables', 'FlightController@timetables');
	Route::post('mypage/confirmReservation', 'FlightController@confirmReservation');
	Route::post('mypage/reserve', 'FlightController@reserve');

	Route::get('mypage/dbtest', 'FlightController@dbtest');

	Route::post('connectionTest', 'ConnectionController@connectionTest');
});