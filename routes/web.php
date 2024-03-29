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
	Route::get('timeentry/{todo}', 'TimeEntryController@edit')->name('timeentry.edit');
	Route::get('timeentry_details/{todo}', 'TimeEntryController@show')->name('timeentry.view');
	Route::patch('timeentry/{todo}', 'TimeEntryController@update');
	Route::delete('delete_todo/{todo}', 'TimeEntryController@destroy')->name('delete_todo');
	Route::post('replicate', 'TimeEntryController@replicate')->name('replicate');

	// ajax
	Route::get('todolists/{projectId}', 'TimeEntryController@todoLists');
	Route::get('todos/{todolistId}', 'TimeEntryController@todos');
	Route::post('post_todos', 'TimeEntryController@postTodos');
	Route::post('delete_todos', 'TimeEntryController@deleteTodos');

	// datatables
	Route::get('datatable_posted_todos', 'TimeEntryController@postedTodos')->name('datatable_posted_todos');

	// users
	Route::get('users', 'UserController@listUsers')->name('users');
	Route::get('loginas/{user}', 'UserController@loginAs')->name('user.loginas');
	Route::get('revert_loginas/{user}', 'UserController@RevertLoginAs')->name('user.revert_loginas');
});
