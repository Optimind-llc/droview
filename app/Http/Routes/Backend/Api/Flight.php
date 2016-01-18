<?php

Route::group(['prefix' => 'flight'], function ()
{
	Route::get('/', 'FlightController@index')->name('admin.flight');
});
