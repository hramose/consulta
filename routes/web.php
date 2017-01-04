<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/','HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/dashboard', 'HomeController@index');

Route::get('/account/edit', 'UserController@edit');
Route::put('/account/edit', 'UserController@update');
Route::post('/account/avatars', 'UserController@avatars');
Route::put('/account/office/{id}', 'OfficeController@update');

Route::post('/patients/photos', 'PatientController@photos');
Route::post('/patients/files', 'PatientController@files');
Route::post('/patients/files/delete', 'PatientController@deleteFiles');
Route::put('/patients/{id}/history', 'PatientController@history');
Route::post('/patients/{id}/medicines', 'PatientController@medicines');
Route::delete('/patients/medicines/{id}', 'PatientController@deleteMedicines');
Route::get('/patients/list', 'PatientController@list');
Route::resource('patients', 'PatientController');

Route::get('/appointments/list', 'AppointmentController@getAppointments');
Route::delete('/appointments/{id}/delete', 'AppointmentController@delete');
Route::resource('appointments', 'AppointmentController');

Route::resource('diseasenotes', 'DiseaseNoteController');
Route::resource('physicalexams', 'PhysicalExamController');
Route::resource('diagnostics', 'DiagnosticController');
Route::resource('signs', 'VitalSignController');

Auth::routes();




