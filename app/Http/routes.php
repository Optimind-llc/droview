<?php

Route::get('/', 'LandingPageController@index')->name('frontend.index');
Route::post('admin/single/validation/user', 'ValidationController@user');
Route::post('admin/single/validation/role', 'ValidationController@role');
Route::get('admin/single/getAddress/{post1}/{post2}', 'ValidationController@getAddress');

//for tets
Route::get('dbtest/{user_id}/{flight_id}', 'DBtestController@dbtest');
Route::get('dbcheck', 'DBtestController@dbcheck');
Route::get('admin/single/dbcheck', 'DBtestController@dbcheck');

Route::get('admin/single/flight/places/{id}/picture', 'Backend\Api\PlaceController@picture');


Route::group(['middleware' => 'auth'], function () {
    Route::get('myprofile', 'ValidationController@profile');
});
Route::group(['middleware' => 'web'], function() {
    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Frontend'], function () {
        require (__DIR__ . '/Routes/Frontend/Frontend.php');
        require (__DIR__ . '/Routes/Frontend/Flight.php');
        require (__DIR__ . '/Routes/Frontend/Access.php');
    });
});
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::group(['namespace' => 'Api', 'prefix' => 'single'], function () {
        require (__DIR__ . '/Routes/Backend/Api/Dashboard.php');
        require (__DIR__ . '/Routes/Backend/Api/Access.php');
        require (__DIR__ . '/Routes/Backend/Api/Flight.php');
        require (__DIR__ . '/Routes/Backend/Api/Pin.php');
    });
});
