<?php

Route::group(['prefix' => 'flight'], function (){
	Route::get('/', 'IndexController@index');
	Route::get('/list', 'IndexController@index');
	Route::get('/timetable/{id}', 'IndexController@index');

	Route::post('/timetables', 'FlightController@timetables');
});
