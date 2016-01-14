<?php

Route::group(['prefix' => 'flight', 'namespace' => 'Flight'], function ()
{
	Route::get('/', 'FlightController@index')->name('admin.flight');
});
