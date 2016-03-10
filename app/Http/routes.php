<?php

Route::get('/', 'LandingPageController@index')->name('frontend.index');

Route::get('mypage/reserved', 'Backend\Flight\FlightController@index')->name('admin.flight');
Route::post('admin/single/validation/user', 'ValidationController@user');
Route::post('admin/single/validation/role', 'ValidationController@role');
Route::get('admin/single/getAddress/{post1}/{post2}', 'ValidationController@getAddress');

//for tets
Route::get('dbtest/{user_id}/{flight_id}', 'DBtestController@dbtest');
Route::get('dbcheck', 'DBtestController@dbcheck');
Route::get('admin/single/dbcheck', 'DBtestController@dbcheck');

Route::group(['middleware' => 'auth'], function () {
    Route::get('myprofile', 'ValidationController@profile');
});


Route::group(['middleware' => 'web'], function() {
    /**
     * Switch between the included languages
     */
    Route::group(['namespace' => 'Language'], function () {
        require (__DIR__ . '/Routes/Language/Language.php');
    });

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

/**
 * Backend Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');

    Route::group(['namespace' => 'Api', 'prefix' => 'single'], function () {
        require (__DIR__ . '/Routes/Backend/Api/Dashboard.php');
        require (__DIR__ . '/Routes/Backend/Api/Access.php');
        require (__DIR__ . '/Routes/Backend/Api/Flight.php');
        require (__DIR__ . '/Routes/Backend/Api/Pin.php');
    });
});
