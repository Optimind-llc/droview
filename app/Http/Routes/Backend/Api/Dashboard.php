<?php
Route::get('/', 'IndexController@index');
Route::get('dashboard', 'IndexController@index')->name('admin.single.dashboard');
