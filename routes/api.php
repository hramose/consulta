<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {

    return $request->user();

})->middleware('auth:api');

//para app medico
Route::prefix('medic')->group(function ()
{
	Route::post('/token', 'Api\Medic\AuthController@token');
	Route::get('/account', 'Api\Medic\UserController@edit')->middleware('auth:api');
	Route::put('/account', 'Api\Medic\UserController@update')->middleware('auth:api');
	Route::put('/account/updatepush', 'Api\Medic\UserController@updatePushToken')->middleware('auth:api');
	Route::post('/account/avatars', 'Api\Medic\UserController@avatars')->middleware('auth:api');
	Route::get('/{user}/patients', 'Api\Medic\AuthController@getPatients')->middleware('auth:api');
	Route::get('/{user}/patients/first', 'Api\Medic\AuthController@getFirstPatient')->middleware('auth:api');
	Route::get('/patients/{id}/history', 'Api\Medic\PatientController@getHistory')->middleware('auth:api');
	Route::put('/patients/{id}', 'Api\Medic\PatientController@update')->middleware('auth:api');
	Route::post('/patients', 'Api\Medic\PatientController@store')->middleware('auth:api');
	Route::delete('/patients/{id}', 'Api\Medic\PatientController@destroy')->middleware('auth:api');
	Route::get('/appointments', 'Api\Medic\AppointmentController@index')->middleware('auth:api');
	Route::post('/appointments', 'Api\Medic\AppointmentController@store')->middleware('auth:api');
	Route::get('/appointments/{appointment}', 'Api\Medic\AppointmentController@show')->middleware('auth:api');
	Route::delete('/appointments/{appointment}/delete', 'Api\Medic\AppointmentController@delete')->middleware('auth:api');
	Route::get('/offices', 'Api\Medic\OfficeController@index')->middleware('auth:api');
	Route::get('/offices/list', 'Api\Medic\OfficeController@getAllOffices')->middleware('auth:api');
	Route::get('/offices/{office}', 'Api\Medic\OfficeController@show')->middleware('auth:api');
	Route::delete('/offices/{office}', 'Api\Medic\OfficeController@destroy')->middleware('auth:api');
	Route::post('/offices', 'Api\Medic\OfficeController@store')->middleware('auth:api');
	Route::post('/offices/request', 'Api\Medic\OfficeController@requestOffice')->middleware('auth:api');
	Route::put('/offices/{office}', 'Api\Medic\OfficeController@update')->middleware('auth:api');
	Route::post('/offices/{office}/assign', 'Api\Medic\OfficeController@assignOffice')->middleware('auth:api');
	Route::get('/{medic}/schedules/list', 'Api\Medic\MedicController@getSchedules')->middleware('auth:api');
	Route::get('/{medic}/appointments/list', 'Api\Medic\MedicController@getAppointments')->middleware('auth:api');
	Route::post('/schedules', 'Api\Medic\ScheduleController@store')->middleware('auth:api');
	Route::post('/schedules/all', 'Api\Medic\ScheduleController@storeAll')->middleware('auth:api');
	Route::put('/schedules/{schedule}', 'Api\Medic\ScheduleController@update')->middleware('auth:api');
	Route::delete('/schedules/{schedule}', 'Api\Medic\ScheduleController@delete')->middleware('auth:api');
	
});

// para app paciente
Route::post('/token', 'Api\AuthController@token');
Route::post('/user/social/register', 'Api\AuthController@registerSocial');
Route::post('/user/register', 'Api\AuthController@register');
Route::get('/user/notifications', 'Api\AuthController@notifications')->middleware('auth:api');
Route::get('/users/{user}/patients', 'Api\AuthController@getPatients')->middleware('auth:api');
Route::get('/users/{user}/patients/first', 'Api\AuthController@getFirstPatient')->middleware('auth:api');
Route::get('/account', 'Api\UserController@edit')->middleware('auth:api');
Route::put('/account/edit', 'Api\UserController@update')->middleware('auth:api');
Route::put('/account/updatepush', 'Api\UserController@updatePushToken')->middleware('auth:api');
Route::delete('/account/patients/{id}', 'Api\UserController@destroyPatient')->middleware('auth:api');
Route::put('/account/patients/{id}', 'Api\UserController@updatePatient')->middleware('auth:api');
Route::post('/account/avatars', 'Api\UserController@avatars')->middleware('auth:api');
Route::post('/account/patients/{id}/medicines', 'Api\PatientController@medicines')->middleware('auth:api');
Route::get('/account/patients/{id}/medicines', 'Api\PatientController@getMedicines')->middleware('auth:api');
Route::delete('/account/patients/medicines/{id}', 'Api\PatientController@deleteMedicines')->middleware('auth:api');
Route::post('/account/patients/{id}/allergies', 'Api\PatientController@allergies')->middleware('auth:api');
Route::get('/account/patients/{id}/allergies', 'Api\PatientController@getAllergies')->middleware('auth:api');
Route::delete('/account/patients/allergies/{id}', 'Api\PatientController@deleteAllergies')->middleware('auth:api');
Route::post('/account/patients/{id}/pressures', 'Api\PatientController@pressures')->middleware('auth:api');
Route::get('/account/patients/{id}/pressures', 'Api\PatientController@getPressures')->middleware('auth:api');
Route::delete('/account/patients/pressures/{id}', 'Api\PatientController@deletePressures')->middleware('auth:api');
Route::post('/account/patients/{id}/sugars', 'Api\PatientController@sugars')->middleware('auth:api');
Route::get('/account/patients/{id}/sugars', 'Api\PatientController@getSugars')->middleware('auth:api');
Route::get('/account/patients/{id}/history', 'Api\PatientController@getHistory')->middleware('auth:api');
Route::get('/account/patients/{id}/labexams', 'Api\PatientController@getLabExams')->middleware('auth:api');
Route::delete('/account/patients/sugars/{id}', 'Api\PatientController@deleteSugars')->middleware('auth:api');
Route::post('/patient/register', 'Api\PatientController@store')->middleware('auth:api');
Route::get('/medics', 'Api\MedicController@search')->middleware('auth:api');
Route::get('/medics/specialities', 'Api\MedicController@getSpecialities')->middleware('auth:api');
Route::get('/medics/{medic}', 'Api\MedicController@show')->middleware('auth:api');
Route::get('/medics/{medic}/schedules/list', 'Api\MedicController@getSchedules')->middleware('auth:api');
Route::get('/medics/{medic}/appointments/list', 'Api\MedicController@getAppointments')->middleware('auth:api');
Route::get('/appointments', 'Api\AppointmentController@index')->middleware('auth:api');
Route::post('/appointments', 'Api\AppointmentController@store')->middleware('auth:api');
Route::get('/appointments/{appointment}', 'Api\AppointmentController@show')->middleware('auth:api');
Route::post('/appointments/{appointment}/reminder', 'Api\AppointmentController@reminder')->middleware('auth:api');
Route::delete('/appointments/{appointment}/delete', 'Api\AppointmentController@delete')->middleware('auth:api');
Route::get('/clinics', 'Api\ClinicController@index')->middleware('auth:api');
Route::get('/clinics/{clinic}', 'Api\ClinicController@show')->middleware('auth:api');
Route::post('/reviews', 'Api\ReviewController@store')->middleware('auth:api');


Route::post('/user/password/phone', 'Api\AuthController@sendResetCodePhone');
Route::post('/user/password/reset', 'Api\AuthController@newPassword');
/*Route::post('/token', function (Request $request) {
	   
	    
	    if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = \Auth::user();

			$token = [
	    		'access_token' => $user->api_token
	    	];

	    	\Auth::logout();

             return json_encode($token);
        }

	    
	    $error = [
	    	'error' => 'Unauthenticated'
	    ];
   
	    
	    return  json_encode($error);

 		
	    
	   
});*/


/*Route::post('/user/register', function (Request $request) {
	    
	    $user = \User::where('email',$request->input('email'))->first();
		
		if($user)
	    {
			$user->name = $request->input('name');
			$user->save();
		}
	
	   
	    if(!$user)
	    {
	    	$user = \User::create([
	    			'name' => $request->input('name'),
	    			'email' => $request->input('email'),
	    			'provider' => 'social',
        			'provider_id' => ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90),
	    			'password' =>'',
	    			'api_token' => ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90)
	    		]);
	    }

 		$token = [
	    	'access_token' => $user->api_token
	    ];
	   
	    return json_encode($token);
});*/

Route::get('/login', function (Request $request) {
	    
	    return $request->user();
	});
