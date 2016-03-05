<?php

Route::group(['prefix' => 'flight'], function (){
	Route::get('/', 'IndexController@index');
	Route::get('/plans', 'IndexController@index');
	Route::get('/plans/{id}/timetable', 'IndexController@index');
	Route::get('/plans/{id}/edit', 'IndexController@index');

	Route::get('/types', 'IndexController@index');

	Route::get('/places', 'IndexController@index');
	Route::get('/places/{id}/edit', 'IndexController@index');
	Route::get('/places/create', 'IndexController@index');

	Route::get('/plans/fetch', 'PlanController@plans');
	Route::get('/plan/{id}/fetch', 'PlanController@plan');
	Route::post('/plan', 'PlanController@create');
	Route::put('/plan/{id}', 'PlanController@update');
	Route::patch('/plan/{id}/deactivate', 'PlanController@deactivate');
	Route::patch('/plan/{id}/activate', 'PlanController@activate');
	Route::delete('/plan/{id}', 'PlanController@delete');

	Route::get('/types/fetch', 'TypeController@types');
	Route::get('/type/fetch', 'TypeController@type');
	Route::post('/type', 'TypeController@create');
	Route::patch('/type/{id}', 'TypeController@update');
	Route::delete('/type/{id}', 'TypeController@delete');
	Route::get('/type/{id}/places/{filter}', 'TypeController@places');
	
	Route::get('/places/fetch', 'PlaceController@places');
	Route::get('/place/fetch', 'PlaceController@place');
	Route::post('/place', 'PlaceController@store');
	Route::post('/place/{id}/update', 'PlaceController@update');
	Route::delete('/place/{id}', 'PlaceController@delete');
	Route::post('/place/picture', 'PlaceController@updatePicture');
	Route::get('/places/{id}/picture', 'PlaceController@picture');

	Route::post('/timetables', 'FlightController@timetables');
	Route::patch('/flight', 'FlightController@update');
	Route::patch('/flight/open', 'FlightController@open');
	Route::delete('/flight/close', 'FlightController@close');
});
