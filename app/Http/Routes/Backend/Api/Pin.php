<?php

Route::group(['prefix' => 'pins'], function () {
	Route::get('/', 'IndexController@index');
	Route::get('list', 'IndexController@index');
	Route::get('generate', 'IndexController@index');

	Route::get('/fetch', 'PinController@index');
	Route::post('generate', 'PinController@generate');
});
