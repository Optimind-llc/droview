<?php

Route::group(['prefix' => 'flight'], function (){
	Route::get('/', 'IndexController@index');
	Route::get('/test', 'IndexController@index');

	Route::post('/timetables', 'FlightController@timetables');
});
