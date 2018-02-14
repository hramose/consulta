<?php
use Edujugon\PushNotification\PushNotification;

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

Route::get('/factura/test/{consecutivo}', 'FacturaElectronicaController@test');
Route::get('/factura/recepcion/{clave}', 'FacturaElectronicaController@recepcion');
Route::post('/factura/response', 'FacturaElectronicaController@haciendaResponse');
Route::get('/factura/decode', 'FacturaElectronicaController@decodemensajehacienda');
// Route::get('/factura/auth', 'FacturaElectronicaController@authToken');
Route::post('/users/{id}/fe/conexion', 'FacturaElectronicaController@authToken');
Route::post('/users/{id}/fe/generate/{consecutivo}', 'FacturaElectronicaController@generateFacturaTest');
Route::get('/users/{id}/fe/recepcion/{clave}', 'FacturaElectronicaController@recepcion');
Route::get('/users/{id}/fe/comprobantes/{clave}', 'FacturaElectronicaController@comprobante');
Route::get('/users/{id}/fe/comprobantes', 'FacturaElectronicaController@comprobantes');

Route::get('/', 'HomeController@index');
Route::post('/changeoffice', 'HomeController@changeOffice');
Route::get('/firma', 'HomeController@firma');
Route::get('/home', 'HomeController@index');
Route::get('/dashboard', 'HomeController@index');
Route::post('/support', 'HomeController@support');
Route::get('/push', function (Request $request) {
    $push = new PushNotification('fcm');
    $userToPush = ['fxpcUYLVUhQ:APA91bG6y1xhaKtdaoHf1MuBlMTWS_EG5EIttv_P1_XesMv0HfuJ_DgFRvorSfUhiSfYjGcFZsc1gGgWDyh5R0BNZxy78JRBYYf6P9Zh62z0RxXl1C9e4s5ODGmbhMWxHpdpNTwsgvYB'];
    $response = $push->setMessage([
        'notification' => [
                'title' => 'Laravel',
                'body' => 'This is the message from Laravel',
                'sound' => 'default'
                ],
        'data' => [
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
        ])
        ->setDevicesToken($userToPush)
        ->send()
        ->getFeedback();
    dd($response);
});

Route::get('/account/edit', 'UserController@edit');
Route::put('/account/edit', 'UserController@update');
Route::post('/account/avatars', 'UserController@avatars');
Route::post('/account/patients', 'UserController@storePatient');
Route::delete('/account/patients/{id}', 'UserController@destroyPatient');
Route::put('/account/patients/{id}', 'UserController@updatePatient');
Route::post('/account/patients/{id}/medicines', 'PatientController@medicines');
Route::delete('/account/patients/medicines/{id}', 'PatientController@deleteMedicines');
Route::post('/account/patients/{id}/labresults', 'PatientController@labResults');
Route::delete('/account/patients/labresults/{id}', 'PatientController@deleteLabResults');
Route::post('/account/patients/{id}/allergies', 'PatientController@allergies');
Route::delete('/account/patients/allergies/{id}', 'PatientController@deleteAllergies');
Route::post('/account/patients/{id}/pressures', 'PatientController@pressures');
Route::delete('/account/patients/pressures/{id}', 'PatientController@deletePressures');
Route::post('/account/patients/{id}/sugars', 'PatientController@sugars');
Route::delete('/account/patients/sugars/{id}', 'PatientController@deleteSugars');

Route::get('/medics/search', 'MedicController@index');
Route::get('/medics/general/search', 'MedicController@search');
Route::get('/medics/specialist/search', 'MedicController@search');
Route::get('/medics/{medic}/offices/{office}/schedule', 'MedicController@schedule');
Route::get('/medics/{medic}/appointments/list', 'MedicController@getAppointments');
Route::get('/medics/{medic}/schedules/list', 'MedicController@getSchedules');
Route::get('/medics/list', 'MedicController@getMedics');

Route::get('/appointments', 'AppointmentController@index');
Route::post('/appointments', 'AppointmentController@store');
Route::put('/appointments/{appointment}', 'AppointmentController@update');
Route::delete('/appointments/{appointment}/delete', 'AppointmentController@delete');
Route::post('/appointments/{appointment}/reminder', 'AppointmentController@reminder');
Route::get('/appointments/reminder', 'AppointmentController@sendReminder');
Route::get('/appointments/{appointment}/poll', 'AppointmentController@poll');
Route::get('/appointments/{appointment}/show', 'AppointmentController@show');
Route::get('/appointments/{id}/print', 'AppointmentController@printSummary');
Route::get('/appointments/{id}/treatment/print', 'AppointmentController@printTreatment');
Route::resource('appointments', 'AppointmentController');

Route::post('/patients', 'PatientController@store');
Route::put('/patients/{patient}', 'PatientController@update');
Route::get('/patients/{id}/labexams', 'PatientController@getLabExams');

Route::get('/expedients/{patient}/show', 'PatientController@expedient');

Route::get('/patients/create', 'PatientController@create');
Route::post('/patients/register', 'PatientController@register');

Route::get('/clinics/search', 'ClinicController@index');
Route::get('/clinics/{office}/schedule', 'ClinicController@schedule');
Route::get('/clinics/{office}/profile', 'Clinic\UserController@profile');
Route::get('/clinics/list', 'ClinicController@getAllOffices');

//Route::get('/polls/{poll}/edit', 'AppointmentController@edit');
Route::get('/medics/{medic}/polls', 'PollController@show');
Route::post('/medics/{medic}/polls', 'PollController@store');
//Route::put('/medics/{medic}/polls/{poll}', 'PollController@update');
Route::post('/polls/send', 'PollController@sendPolls');
Route::resource('polls', 'PollController');

//Route::group(['as'=>'medic.','prefix' => 'medic', 'middleware'=>'authByRole:medico'], function ()
Route::prefix('medic')->middleware('authByRole:medico')->group(function () {
    Route::get('/account/edit', 'Medic\UserController@edit');
    Route::put('/account/edit', 'Medic\UserController@update');
    Route::post('/account/assistant', 'Medic\UserController@addAssistant');
    Route::put('/account/assistant/{assistant}', 'Medic\UserController@updateAssistant');
    Route::get('/account/assistants', 'Medic\UserController@getAssistants');
    Route::get('/account/consultorios', 'Medic\UserController@getConsultoriosIndependientes');
    Route::post('/account/patients', 'Medic\UserController@storePatient');
    Route::post('/account/{id}/configfactura', 'Medic\UserController@addConfigFactura');
    Route::put('/account/{id}/configfactura', 'Medic\UserController@updateConfigFactura');
    Route::delete('/users/{id}/configfactura', 'Medic\UserController@deleteConfigFactura');

    Route::delete('/account/assistants/{assistant}', 'Medic\UserController@deleteAssistant');
    Route::put('/account/settings', 'Medic\UserController@updateSettings');

    Route::post('/account/avatars', 'Medic\UserController@avatars');
    Route::post('/account/offices', 'Medic\OfficeController@store');
    Route::post('/account/offices/requests', 'Medic\OfficeController@requestOffice');
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
    Route::post('/patients/{id}/labresults', 'Medic\PatientController@labResults');
    Route::delete('/patients/labresults/{id}', 'Medic\PatientController@deleteLabResults');
    Route::post('/patients/{id}/labexams', 'Medic\PatientController@labExams');
    Route::get('/patients/{id}/labexams', 'Medic\PatientController@getLabExams');
    Route::delete('/patients/labexams/{id}', 'Medic\PatientController@deleteLabExams');
    Route::post('/patients/{id}/add', 'Medic\PatientController@addToYourPatients');
    Route::get('/patients/list', 'Medic\PatientController@list');
    Route::resource('patients', 'Medic\PatientController');

    Route::get('/schedules/list', 'Medic\ScheduleController@getSchedules');
    Route::delete('/schedules/{id}/delete', 'Medic\ScheduleController@delete');
    Route::post('/schedules/copyto/{week}', 'Medic\ScheduleController@copySchedule');
    Route::resource('schedules', 'Medic\ScheduleController');

    Route::get('/appointments/list', 'Medic\AppointmentController@getAppointments');
    Route::get('/appointments/{id}/print', 'Medic\AppointmentController@printSummary');
    Route::get('/appointments/{id}/treatment/print', 'Medic\AppointmentController@printTreatment');
    Route::get('/appointments/{id}/pdf', 'Medic\AppointmentController@pdfSummary');
    Route::post('/appointments/{id}/pdf', 'Medic\AppointmentController@pdf');
    Route::delete('/appointments/{id}/delete', 'Medic\AppointmentController@delete');
    Route::put('/appointments/{id}/noshows', 'Medic\AppointmentController@noShows');
    Route::put('/appointments/{id}/finished', 'Medic\AppointmentController@finished');
    Route::put('/appointments/{id}/viewed', 'Medic\AppointmentController@viewed');
    Route::get('/appointments/{id}/create', 'Medic\AppointmentController@createFrom');
    //Route::get('/clinics/{clinic}/appointments', 'Medic\AppointmentController@appointmentsFromClinic');
    Route::get('/appointments/calendar', 'Medic\AppointmentController@create');
    Route::resource('appointments', 'Medic\AppointmentController');

    Route::get('/invoices/services/list', 'Medic\InvoiceController@getServices');
    Route::post('/invoices/services', 'Medic\InvoiceController@saveService');
    Route::put('/invoices/services/{id}', 'Medic\InvoiceController@updateService');
    Route::delete('/invoices/services/{id}', 'Medic\InvoiceController@deleteService');

    Route::get('/invoices', 'Medic\InvoiceController@index');
    Route::post('/balance', 'Medic\InvoiceController@balance');
    Route::put('/invoices/{id}', 'Medic\InvoiceController@update');
    Route::get('/invoices/{id}/details', 'Medic\InvoiceController@getDetails');
    Route::get('/invoices/{id}/print', 'Medic\InvoiceController@print');
    Route::get('/invoices/{id}/ticket', 'Medic\InvoiceController@ticket');
    Route::get('/invoices/{id}/recepcion', 'Medic\InvoiceController@recepcion');

    Route::resource('invoices', 'Medic\InvoiceController');

    Route::resource('diseasenotes', 'Medic\DiseaseNoteController');
    Route::resource('physicalexams', 'Medic\PhysicalExamController');
    Route::resource('diagnostics', 'Medic\DiagnosticController');
    Route::resource('treatments', 'Medic\TreatmentController');
    Route::resource('allergies', 'Medic\AllergyController');
    Route::resource('pathologicals', 'Medic\PathologicalController');
    Route::resource('nopathologicals', 'Medic\NopathologicalController');
    Route::resource('heredos', 'Medic\HeredoController');
    Route::resource('ginecos', 'Medic\GinecoController');
    Route::resource('signs', 'Medic\VitalSignController');
    Route::resource('notes', 'Medic\NoteController');

    Route::get('/reports', 'Medic\ReportsController@index');
    Route::get('/reports/generate', 'Medic\ReportsController@generate');
    Route::get('/reports/incomes/generate', 'Medic\ReportsController@incomes');

    Route::get('/payments/{id}/details', 'Medic\PaymentController@details');
    Route::post('/payments/{id}/pay', 'Medic\PaymentController@pay');
    Route::get('/payments/{id}/create', 'Medic\PaymentController@create');
    Route::get('/payments/create', 'Medic\PaymentController@create');
    Route::post('/payments/receipt', 'Medic\PaymentController@purchaseResponse');

    Route::get('/subscriptions/{id}/buy', 'Medic\SubscriptionController@buy');
    Route::get('/subscriptions/{id}/change', 'Medic\SubscriptionController@change');

    Route::get('/subscriptions/list', 'Medic\SubscriptionController@list');
});

Route::prefix('clinic')->middleware('authByRole:clinica,asistente')->group(function () {
    Route::get('/account/edit', 'Clinic\UserController@edit');
    Route::put('/account/edit', 'Clinic\UserController@update');
    Route::post('/account/assistant', 'Clinic\UserController@addAssistant');
    Route::put('/account/assistant/{assistant}', 'Clinic\UserController@updateAssistant');
    Route::delete('/account/assistants/{assistant}', 'Clinic\UserController@deleteAssistant');
    Route::get('/account/assistants', 'Clinic\UserController@getAssistants');

    //Route::put('/account/settings', 'Clinic\UserController@updateSettings');

    Route::post('/account/avatars', 'Clinic\UserController@avatars');
    Route::post('/account/offices', 'Clinic\UserController@updateClinic');
    Route::put('/account/offices/{id}/notification', 'Clinic\OfficeController@updateOfficeNotification');
    //Route::put('/account/offices/{id}', 'Clinic\UserController@updateClinic');
    Route::get('/specialities/list', 'Clinic\UserController@getSpecialities');
    //Route::post('/account/patients', 'UserController@storePatient');
    //Route::delete('/account/patients/{id}', 'UserController@destroyPatient');
    //Route::put('/account/patients/{id}', 'UserController@updatePatient');

    Route::post('/patients/photos', 'Clinic\PatientController@photos');
    Route::post('/patients/files', 'Clinic\PatientController@files');
    Route::post('/patients/files/delete', 'Clinic\PatientController@deleteFiles');
    Route::put('/patients/{id}/history', 'Clinic\PatientController@history');
    Route::post('/patients/{id}/medicines', 'Clinic\PatientController@medicines');
    Route::delete('/patients/medicines/{id}', 'Clinic\PatientController@deleteMedicines');
    Route::post('/patients/{id}/labresults', 'Clinic\PatientController@labResults');
    Route::delete('/patients/labresults/{id}', 'Clinic\PatientController@deleteLabResults');
    Route::post('/patients/{id}/add', 'Clinic\PatientController@addToYourPatients');
    Route::get('/patients/list', 'Clinic\PatientController@list');
    Route::get('/patients/verify', 'Clinic\PatientController@verifyIsPatient');
    Route::get('/patients/{patient}/invoices', 'Clinic\PatientController@invoices');
    Route::resource('patients', 'Clinic\PatientController');
    Route::put('/invoices/{id}', 'Clinic\InvoiceController@update');
    Route::get('/invoices/{id}/details', 'Clinic\InvoiceController@getDetails');
    Route::get('/invoices/{id}/print', 'Clinic\InvoiceController@print');
    Route::get('/invoices/{id}/ticket', 'Clinic\InvoiceController@ticket');

    Route::get('/appointments/list', 'Clinic\AppointmentController@getAppointments');
    Route::get('/appointments/{id}/print', 'Clinic\AppointmentController@printSummary');
    Route::delete('/appointments/{id}/delete', 'Clinic\AppointmentController@delete');
    Route::resource('appointments', 'Clinic\AppointmentController');

    Route::post('/medics/{medic}/offices/{office}/assign', 'Clinic\MedicController@assignOffice');
    Route::post('/medics/{medic}/offices/{office}/unassign', 'Clinic\MedicController@unassignOffice');

    Route::post('/medics/{medic}/assign', 'Clinic\MedicController@assignOfficeFromNotifications');
    Route::put('/medics/{medic}/commission/', 'Clinic\MedicController@updateCommission');
    Route::get('/medics/list', 'Clinic\MedicController@getMedics');
    Route::resource('medics', 'Clinic\MedicController');

    Route::get('/reports', 'Clinic\ReportsController@index');
    Route::get('/reports/generate', 'Clinic\ReportsController@generate');
    Route::get('/reports/incomes/generate', 'Clinic\ReportsController@incomes');

    Route::get('/register/office', 'Clinic\RegisterController@showRegistrationOfficeForm');
    Route::post('/register/office', 'Clinic\RegisterController@registerOffice');
});

Route::prefix('assistant')->middleware('authByRole:asistente,clinica')->group(function () {
    Route::get('/account/edit', 'Assistant\UserController@edit');
    Route::put('/account/edit', 'Assistant\UserController@update');
    Route::put('/account/assistant', 'Assistant\UserController@addAssistant');
    //Route::put('/account/settings', 'Assistant\UserController@updateSettings');

    Route::post('/account/avatars', 'Assistant\UserController@avatars');
    Route::put('/account/offices/{id}', 'Assistant\UserController@updateClinic');
    Route::get('/specialities/list', 'Assistant\UserController@getSpecialities');
    //Route::post('/account/patients', 'UserController@storePatient');
    //Route::delete('/account/patients/{id}', 'UserController@destroyPatient');
    //Route::put('/account/patients/{id}', 'UserController@updatePatient');

    Route::post('/patients/photos', 'Assistant\PatientController@photos');
    Route::post('/patients/files', 'Assistant\PatientController@files');
    Route::post('/patients/files/delete', 'Assistant\PatientController@deleteFiles');
    Route::put('/patients/{id}/history', 'Assistant\PatientController@history');
    Route::post('/patients/{id}/medicines', 'Assistant\PatientController@medicines');
    Route::delete('/patients/medicines/{id}', 'Assistant\PatientController@deleteMedicines');
    Route::post('/patients/{id}/add', 'Assistant\PatientController@addToYourPatients');
    Route::get('/patients/list', 'Assistant\PatientController@list');
    Route::resource('patients', 'Assistant\PatientController');

    Route::get('/appointments/list', 'Assistant\AppointmentController@getAppointments');
    Route::get('/appointments/{id}/print', 'Assistant\AppointmentController@printSummary');
    Route::delete('/appointments/{id}/delete', 'Assistant\AppointmentController@delete');
    Route::put('/appointments/{id}/viewed', 'Assistant\AppointmentController@viewed');
    Route::resource('appointments', 'Assistant\AppointmentController');

    Route::post('/medics/{medic}/offices/{office}/assign', 'Assistant\MedicController@assignOffice');
    Route::get('/medics/list', 'Assistant\MedicController@getMedics');
    Route::resource('medics', 'Assistant\MedicController');

    //Route::get('/invoices/services/list', 'Assistant\InvoiceController@getServices');
    //Route::post('/invoices/services', 'Assistant\InvoiceController@saveService');
    Route::put('/invoices/{id}', 'Assistant\InvoiceController@update');
    Route::get('/invoices/{id}/details', 'Assistant\InvoiceController@getDetails');
    Route::get('/invoices/{id}/print', 'Assistant\InvoiceController@print');
    Route::get('/invoices/{id}/ticket', 'Assistant\InvoiceController@ticket');
    Route::get('/medics/{medic}/invoices', 'Assistant\InvoiceController@show');
    Route::post('/medics/{medic}/balance', 'Assistant\InvoiceController@balance');
    Route::get('/invoices/balance', 'Assistant\InvoiceController@generalBalance');
    Route::get('/patients/{patient}/invoices', 'Assistant\InvoiceController@patientInvoices');
    Route::resource('invoices', 'Assistant\InvoiceController');
});

Route::prefix('admin')->middleware('authByRole:administrador')->group(function () {
    foreach (['active', 'inactive', 'trial', 'notrial'] as $key) {
        Route::post('users/{user}/' . $key, [
            'as' => 'users.' . $key,
            'uses' => 'Admin\UserController@' . $key,
        ]);
    }
    Route::put('/configuration', 'Admin\UserController@updateConfig');

    Route::get('/reports/patients', 'Admin\ReportsController@patients');
    Route::get('/reports/patients/generate', 'Admin\ReportsController@generatePatients');
    Route::get('/reports/patients/{medic}/generate', 'Admin\ReportsController@generatePatients');

    Route::get('/reports/medics', 'Admin\ReportsController@medics');
    Route::get('/reports/medics/generate', 'Admin\ReportsController@generateMedics');
    Route::get('/reports/medics/{medic}/generate', 'Admin\ReportsController@generateMedics');

    Route::get('/reports/clinics', 'Admin\ReportsController@clinics');
    Route::get('/reports/clinics/generate', 'Admin\ReportsController@generateClinics');
    Route::get('/reports/incomes/generate', 'Admin\ReportsController@generateIncomes');
    Route::get('/offices/list', 'ClinicController@getAllOffices');

    Route::get('/reviews', 'Admin\ReviewController@index');
    Route::delete('/reviews/{id}', 'Admin\ReviewController@delete');

    Route::get('/offices/requests', 'Admin\OfficeController@index');
    Route::delete('/offices/requests/{id}', 'Admin\OfficeController@delete');
    foreach (['active', 'inactive', 'trial', 'notrial'] as $key) {
        Route::post('/offices/requests/{request}/' . $key, [
            'as' => 'requestOffices.' . $key,
            'uses' => 'Admin\OfficeController@' . $key,
        ]);
    }
    Route::get('/clinics/requests', 'Admin\UserController@adminClinicRequests');
    foreach (['active', 'inactive', 'trial', 'notrial'] as $key) {
        Route::post('/clinics/requests/{request}/' . $key, [
            'as' => 'requestAdminClinic.' . $key,
            'uses' => 'Admin\UserController@' . $key,
        ]);
    }
    Route::get('/medics/requests', 'Admin\UserController@medicRequests');
    foreach (['active', 'inactive', 'trial', 'notrial'] as $key) {
        Route::post('/clinics/requests/{request}/' . $key, [
            'as' => 'requestMedic.' . $key,
            'uses' => 'Admin\UserController@' . $key,
        ]);
    }
    Route::post('/users/{id}/subscriptions', 'Admin\UserController@addSubscription');
    Route::put('/users/{id}/subscriptions', 'Admin\UserController@updateSubscription');
    Route::delete('/users/{id}/subscriptions', 'Admin\UserController@deleteSubscription');

    Route::post('/users/{id}/configfactura', 'Admin\UserController@addConfigFactura');
    Route::put('/users/{id}/configfactura', 'Admin\UserController@updateConfigFactura');
    Route::delete('/users/{id}/configfactura', 'Admin\UserController@deleteConfigFactura');

    Route::resource('plans', 'Admin\PlanController');
    Route::resource('reports', 'Admin\ReportsController');
    Route::resource('users', 'Admin\UserController');
});

Route::get('/register', 'Auth\RegisterPatientController@showRegistrationForm');
Route::post('/register', 'Auth\RegisterPatientController@register');

Route::get('/medic/register', 'Auth\RegisterController@showRegistrationForm');
Route::post('/medic/register', 'Auth\RegisterController@register');

Route::get('/clinic/register', 'Clinic\RegisterController@showRegistrationForm');
Route::post('/clinic/register/admin', 'Clinic\RegisterController@registerAdmin'); // registro nuevo de admin y clinic
Route::post('/clinic/register', 'Auth\RegisterClinicController@register'); // registro profile temp clinica ya creada

//login for patient
Route::get('/user/login', 'Auth\LoginPatientController@login');
Route::post('/user/login', 'Auth\LoginPatientController@postLogin');
Route::post('/user/logout', 'Auth\LoginPatientController@logout');

Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('/user/password/reset', 'Auth\LoginPatientController@resetPassword');
Route::post('/user/password/phone', 'Auth\LoginPatientController@sendResetCodePhone');
Route::get('/user/password/reset/code', 'Auth\LoginPatientController@resetCode');
Route::post('/user/password/reset', 'Auth\LoginPatientController@newPassword');

//login for other roles users
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Password Reset Routes... for medic
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
//Auth::routes();
//Route::get('patient/auth/{provider}', 'Auth\AuthPatientController@redirectToProvider');
//Route::get('patient/auth/{provider}/callback', 'Auth\AuthPatientController@handleProviderCallback');
