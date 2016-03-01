<?php

/*
| Preliminary routes for development
| To be removed or put under middleware control
| for production
*/
Route::get('/welcome', function() {
	return Session::get('Code');
});

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

	Route::get('/ContactTeam', 'ContactController@contact_team');
	Route::post('/SendMessage', 'ContactController@send_message');

	Route::get('/ResetPassword', 'GateController@reset_password');
	Route::post('/MailForPassword', 'GateController@mail_for_password');

	// Registration routes...
	/* urls: as should be
	Route::post('/register', 'GateController@start_registration');
	Route::get('/registration/welcome', 'GateController@show_welcome');
	Route::get('/registration/agreement', 'GateController@from_welcome');
	Route::get('/registration/form', 'GateController@req_patient_data');
	Route::post('/registration/form', 'GateController@save_patient_data'); */

	/* urls: conform to model (...) */
	Route::post('/StartRegistration', 'GateController@start_registration');
	Route::get('/FromWelcome', 'GateController@from_welcome');
	Route::get('/Accepted', 'GateController@req_patient_data');
	Route::post('/SavePatientData', 'GateController@save_patient_data');

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

	// Experimental for M2
	Route::get('/Diary/{name?}','DiaryController@show');
	Route::get('/Profile/{name?}','PatientController@profile');
	Route::post('/SendMail','ContactController@message_to_patients');
	Route::post('/MassAction/mail','ContactController@mail_editor');
	Route::post('/SaveProfile','PatientController@save_profile');
});

Route::group(['middleware' => ['web', 'auth']], function () {
	Route::get('/Home', 'AuxController@home');
});

Route::any('patient_list/data', 'PatientListController@anyData')->name('datatables.data');