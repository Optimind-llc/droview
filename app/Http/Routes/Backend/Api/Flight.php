<?php

Route::group(['prefix' => 'flight'], function (){
	Route::get('/', 'IndexController@index');
	Route::get('/plans', 'IndexController@index');
	Route::get('/types', 'IndexController@index');
	Route::get('/places', 'IndexController@index');
	Route::get('/timetable/{id}', 'IndexController@index');
	Route::get('/plan/{id}/edit', 'IndexController@index');

	Route::get('/plans/fetch', 'PlanController@plans');
	Route::get('/plan/{id}/fetch', 'PlanController@plan');
	Route::post('/plan', 'PlanController@create');
	Route::put('/plan/{id}', 'PlanController@update');
	Route::patch('/plan/{id}/deactivate', 'PlanController@deactivate');
	Route::patch('/plan/{id}/activate', 'PlanController@activate');
	Route::delete('/plan/{id}', 'PlanController@delete');

	Route::get('/type/{id}/places/{filter}', 'TypeController@getPlacesByType');
	Route::post('/type', 'TypeController@create');
	
	Route::post('/timetables', 'FlightController@timetables');
	Route::post('/flight', 'FlightController@open');
	Route::delete('/flight', 'FlightController@close');
});
