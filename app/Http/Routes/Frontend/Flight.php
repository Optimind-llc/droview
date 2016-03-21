<?php

/**
 * Frontend Flights
 */
Route::get('droview', 'FrontendController@index')->name('droview.index');

Route::group(['middleware' => 'auth'], function () {

	Route::get('droview/reserve', 'ApiController@index')->name('frontend.droview');
	Route::get('droview/reserved', 'ApiController@index');
	Route::get('droview/timetable/{id}', 'ApiController@index');
	Route::get('droview/log', 'ApiController@index');
	Route::get('droview/log/ticket', 'ApiController@index');
	Route::get('droview/log/reservation', 'ApiController@index');
	Route::get('droview/profile', 'ApiController@index');

	Route::get('droview/plans/fetch', 'PlanController@plans');
	Route::get('droview/plan/{id}/fetch', 'PlanController@plan');

	Route::get('droview/log/ticket/fetch', 'LogController@ticket');
	Route::get('droview/log/reservation/fetch', 'LogController@reservation');

	Route::post('droview/timetables', 'FlightController@timetables');
	Route::post('droview/confirmReservation', 'FlightController@confirmReservation');
	Route::post('droview/reserve', 'FlightController@reserve');

	Route::get('droview/dbtest', 'FlightController@dbtest');

	Route::post('connectionTest', 'ConnectionController@connectionTest');
});