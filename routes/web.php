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

    // project
    Route::get('project/{projectId}', 'ProjectController@show')->name('project_hours');

    // time entry
    Route::get('timeentry', 'TimeEntryController@index')->name('timeentry');
    Route::post('timeentry', 'TimeEntryController@store');
    Route::delete('delete_todo/{todo}', 'TimeEntryController@destroy')->name('delete_todo');

    // ajax
    Route::get('todolists/{projectId}', 'TimeEntryController@todoLists');
    Route::get('todos/{todolistId}', 'TimeEntryController@todos');

    // datatables
    Route::get('datatable_posted_todos', 'TimeEntryController@postedTodos')->name('datatable_posted_todos');
});
