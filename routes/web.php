<?php

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {

    // settings
    Route::get('settings', 'UserController@index')->name('settings');
    Route::post('settings', 'UserController@update');

    // refresh data
    Route::get('refresh_data', 'HomeController@refresh')->name('refresh_data');

    // time entry
    Route::get('timeentry', 'TimeEntryController@index')->name('timeentry');
});
