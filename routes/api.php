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


Route::post('/token', 'Api\AuthController@token');
Route::post('/user/social/register', 'Api\AuthController@registerSocial');
Route::post('/user/register', 'Api\AuthController@register');
Route::post('/patient/register', 'Api\PatientController@store');
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
