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
Route::get('/medics/general/search', 'MedicController@search');
Route::get('/medics/specialist/search', 'MedicController@search');
Route::get('/medics/{medic}/offices/{office}/schedule', 'MedicController@schedule');
Route::get('/medics/{medic}/appointments/list', 'MedicController@getAppointments');
Route::get('/medics/{medic}/schedules/list', 'MedicController@getSchedules');

Route::get('/appointments', 'AppointmentController@index');
Route::post('/appointments', 'AppointmentController@store');
Route::put('/appointments/{appointment}', 'AppointmentController@update');
Route::delete('/appointments/{appointment}/delete', 'AppointmentController@delete');
Route::post('/appointments/{appointment}/reminder', 'AppointmentController@reminder');
Route::get('/appointments/reminder', 'AppointmentController@sendReminder');
Route::resource('appointments', 'AppointmentController');

Route::post('/patients', 'PatientController@store');
Route::put('/patients/{patient}', 'PatientController@update');

Route::get('/patients/create', 'PatientController@create');
Route::post('/patients/register', 'PatientController@register');

Route::get('/clinics/search', 'ClinicController@index');

//Route::group(['as'=>'medic.','prefix' => 'medic', 'middleware'=>'authByRole:medico'], function ()
Route::prefix('medic')->middleware('authByRole:medico')->group(function ()
{

	Route::get('/account/edit', 'Medic\UserController@edit');
	Route::put('/account/edit', 'Medic\UserController@update');
	Route::put('/account/settings', 'Medic\UserController@updateSettings');
	
	Route::post('/account/avatars', 'Medic\UserController@avatars');
	Route::post('/account/offices', 'Medic\OfficeController@store');
	Route::post('/account/offices/{office}/assign', 'Medic\OfficeController@assignOffice');
	Route::delete('/account/offices/{id}', 'Medic\OfficeController@destroy');
	Route::get('/account/offices/list', 'Medic\OfficeController@getOffices');
	Route::get('/offices/list', 'Medic\OfficeController@getAllOffices');
	Route::put('/account/offices/{id}', 'Medic\OfficeController@update');
	Route::put('/account/offices/{id}/notification', 'Medic\OfficeController@updateOfficeNotification');

	Route::post('/patients/photos', 'Medic\PatientController@photos');
	Route::post('/patients/files', 'Medic\PatientController@files');
	Route::post('/patients/files/delete', 'Medic\PatientController@deleteFiles');
	Route::put('/patients/{id}/history', 'Medic\PatientController@history');
	Route::post('/patients/{id}/medicines', 'Medic\PatientController@medicines');
	Route::delete('/patients/medicines/{id}', 'Medic\PatientController@deleteMedicines');
	Route::post('/patients/{id}/add', 'Medic\PatientController@addToYourPatients');
	Route::get('/patients/list', 'Medic\PatientController@list');
	Route::resource('patients', 'Medic\PatientController');

	Route::get('/schedules/list', 'Medic\ScheduleController@getSchedules');
	Route::delete('/schedules/{id}/delete', 'Medic\ScheduleController@delete');
	Route::resource('schedules', 'Medic\ScheduleController');

	Route::get('/appointments/list', 'Medic\AppointmentController@getAppointments');
	Route::get('/appointments/{id}/print', 'Medic\AppointmentController@printSummary');
	Route::delete('/appointments/{id}/delete', 'Medic\AppointmentController@delete');
	Route::resource('appointments', 'Medic\AppointmentController');

	Route::resource('diseasenotes', 'Medic\DiseaseNoteController');
	Route::resource('physicalexams', 'Medic\PhysicalExamController');
	Route::resource('diagnostics', 'Medic\DiagnosticController');
	Route::resource('signs', 'Medic\VitalSignController');

});

Route::prefix('clinic')->middleware('authByRole:clinica')->group(function ()
{

	Route::get('/account/edit', 'Clinic\UserController@edit');
	Route::put('/account/edit', 'Clinic\UserController@update');
	Route::put('/account/settings', 'Clinic\UserController@updateSettings');
	
	Route::post('/account/avatars', 'Clinic\UserController@avatars');
	Route::post('/account/patients', 'UserController@storePatient');
	Route::delete('/account/patients/{id}', 'UserController@destroyPatient');
	Route::put('/account/patients/{id}', 'UserController@updatePatient');
	
	Route::post('/patients/photos', 'Clinic\PatientController@photos');
	Route::post('/patients/files', 'Clinic\PatientController@files');
	Route::post('/patients/files/delete', 'Clinic\PatientController@deleteFiles');
	Route::put('/patients/{id}/history', 'Clinic\PatientController@history');
	Route::post('/patients/{id}/medicines', 'Clinic\PatientController@medicines');
	Route::delete('/patients/medicines/{id}', 'Clinic\PatientController@deleteMedicines');
	Route::post('/patients/{id}/add', 'Clinic\PatientController@addToYourPatients');
	Route::get('/patients/list', 'Clinic\PatientController@list');
	Route::resource('patients', 'Clinic\PatientController');

	Route::get('/appointments/list', 'Clinic\AppointmentController@getAppointments');
	Route::get('/appointments/{id}/print', 'Clinic\AppointmentController@printSummary');
	Route::delete('/appointments/{id}/delete', 'Clinic\AppointmentController@delete');
	Route::resource('appointments', 'Clinic\AppointmentController');

	Route::resource('medics', 'Clinic\MedicController');

});

Route::prefix('admin')->middleware('authByRole:administrador')->group(function ()
{
	foreach (['active', 'inactive'] as $key)
    {
        Route::post('users/{user}/' . $key, array(
            'as'   => 'users.' . $key,
            'uses' => 'Admin\UserController@' . $key,
        ));
    }
	Route::resource('users', 'Admin\UserController');

});

Route::get('/register', 'Auth\RegisterPatientController@showRegistrationForm');
Route::post('/register', 'Auth\RegisterPatientController@register');

Route::get('/medic/register', 'Auth\RegisterController@showRegistrationForm');
Route::post('/medic/register', 'Auth\RegisterController@register');

Route::get('/clinic/register', 'Auth\RegisterClinicController@showRegistrationForm');
Route::post('/clinic/register', 'Auth\RegisterClinicController@register');


Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

//Auth::routes();




