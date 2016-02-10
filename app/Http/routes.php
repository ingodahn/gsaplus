<?php

/*
| Preliminary routes for development
| To be removed or put under middleware control
| for production
*/
Route::get('/welcome', function() {
	return Session::get('Code');
});

Route::post('/SetSlots', 'PatientListController@set_slots');
Route::get('/patient_list', 'PatientListController@show');
Route::get('/admin_home',function() {
	return view('admin.home');
});
Route::get('/AdminCodes','AdminController@admin_codes');
Route::get('/AdminUsers','AdminController@admin_users');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'GateController@enter_system');
	Route::post('/StartRegistration', 'GateController@start_registration');
	Route::get('/ResetPassword', 'GateController@reset_password');
	Route::get('/ContactTeam', 'ContactController@contact_team');
	Route::post('/SendMessage', 'ContactController@send_message');
	Route::post('/MailForPassword', 'GateController@mail_for_password');
	Route::get('/FromWelcome', 'GateController@from_welcome');
	Route::get('/Accepted', 'GateController@req_patient_data');
	Route::post('/SavePatientData', 'GateController@save_patient_data');
	Route::get('/GetResetCode', 'GateController@get_reset_code');

	// Authentication routes...
	Route::get('/Login', 'Auth\AuthController@getLogin');
	Route::post('/Login', 'Auth\AuthController@postLogin');

	Route::get('/Logout', 'Auth\AuthController@logout');

	// Password reset link request routes...
	Route::get('password/email', 'Auth\PasswordController@getEmail');
	Route::post('password/email', 'Auth\PasswordController@postEmail');

	// Password reset routes...
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset');
});

Route::group(['middleware' => ['web', 'auth']], function () {
	Route::get('/Home', 'AuxController@home');
});
