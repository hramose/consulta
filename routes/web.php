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
Route::post('/account/patients', 'UserController@storePatient');
Route::delete('/account/patients/{id}', 'UserController@destroyPatient');
Route::put('/account/patients/{id}', 'UserController@updatePatient');

Route::get('/medics/search', 'MedicController@index');
Route::get('/medics/{medic}/schedule', 'MedicController@schedule');
Route::get('/medics/{medic}/appointments/list', 'MedicController@getAppointments');
Route::post('/medics/appointments', 'MedicController@storeAppointment');
Route::put('/medics/appointments/{appointment}', 'MedicController@updateAppointment');
Route::delete('/medics/appointments/{appointment}/delete', 'MedicController@deleteAppointment');
Route::get('/clinics/search', 'ClinicController@index');

Route::post('/patients', 'MedicController@storePatient');
Route::put('/patients/{patient}', 'MedicController@updatePatient');


Route::group(['as'=>'medic.','prefix' => 'medic', 'middleware'=>'authByRole:medico'], function ()
{

	Route::get('/account/edit', 'Medic\UserController@edit');
	Route::put('/account/edit', 'Medic\UserController@update');
	Route::post('/account/avatars', 'Medic\UserController@avatars');
	Route::post('/account/offices', 'Medic\OfficeController@store');
	Route::delete('/account/offices/{id}', 'Medic\OfficeController@destroy');
	Route::get('/account/offices/list', 'Medic\OfficeController@getOffices');
	Route::put('/account/offices/{id}', 'Medic\OfficeController@update');

	Route::post('/patients/photos', 'Medic\PatientController@photos');
	Route::post('/patients/files', 'Medic\PatientController@files');
	Route::post('/patients/files/delete', 'Medic\PatientController@deleteFiles');
	Route::put('/patients/{id}/history', 'Medic\PatientController@history');
	Route::post('/patients/{id}/medicines', 'Medic\PatientController@medicines');
	Route::delete('/patients/medicines/{id}', 'Medic\PatientController@deleteMedicines');
	Route::get('/patients/list', 'Medic\PatientController@list');
	Route::resource('patients', 'Medic\PatientController');

	Route::get('/appointments/list', 'Medic\AppointmentController@getAppointments');
	Route::get('/appointments/{id}/print', 'Medic\AppointmentController@printSummary');
	Route::delete('/appointments/{id}/delete', 'Medic\AppointmentController@delete');
	Route::resource('appointments', 'Medic\AppointmentController');

	Route::resource('diseasenotes', 'Medic\DiseaseNoteController');
	Route::resource('physicalexams', 'Medic\PhysicalExamController');
	Route::resource('diagnostics', 'Medic\DiagnosticController');
	Route::resource('signs', 'Medic\VitalSignController');

});

Auth::routes();




