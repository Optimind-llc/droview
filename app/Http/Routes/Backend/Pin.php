<?php

Route::group(['prefix' => 'pin', 'namespace' => 'Pin'], function ()
{
	Route::get('/', 'PinController@index')->name('admin.pin');		
	Route::post('/html', 'PinController@outPutHTML')->name('admin.pin.html');					
	Route::post('/mail', 'PinController@outPutMail')->name('admin.pin.mail');					
});
