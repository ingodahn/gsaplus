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
// the following routes are working on the current date
// -> middleware test.date
Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'GateController@enter_system');

	Route::get('/ContactTeam', 'ContactController@contact_team');
	Route::post('/SendMessage', 'ContactController@send_message');

	Route::get('/impressum', function() {
		return view('system.impressum');
	});
	Route::get('/privacy', function() {
		return view('system.privacy');
	});
	Route::get('/about', function() {
		return view('system.about');
	});
	Route::get('/info', function() {
		return view('system.info');
	});

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

	Route::group(['prefix' => '/password'], function() {
		// Password reset link request routes...
		Route::get('email', 'Auth\PasswordController@getEmail');
		Route::post('email', 'Auth\PasswordController@postEmail');

		// Password reset routes...
		Route::get('reset/{token}', 'Auth\PasswordController@getReset');
		Route::post('reset', 'Auth\PasswordController@postReset');
	});

	// especially the test page needs to operate on the current date (!)
	// -> middleware test.date isn't active
	// (session is needed for alerts)
	Route::group(['prefix' => '/test'], function() {
		Route::get('', 'TestController@showOverview');

		// Password reset link request routes...
		Route::post('login/{user}', 'TestController@loginAs');
		Route::post('next-date/{patient}', 'TestController@setAssignmentRelatedTestDate');
		Route::post('next-date/{patient}/{daysToAdd}', 'TestController@setAssignmentRelatedTestDate');
		Route::post('settings', 'TestController@changeSettings');

		Route::post('send-reminders/{option}', 'TestController@sendReminders');

		Route::get('dump-info/{user}', 'TestController@dumpInfo');
	});

});

// the following routes are working with the test date
Route::group(['middleware' => ['web', 'auth', 'test.date']], function () {
	Route::get('/Home', 'AuxController@home');

	Route::get('/Diary/{name?}','DiaryController@show');
	Route::get('/Profile/{name?}','PatientController@profile');
	Route::post('/SendMail','ContactController@message_to_patients');
	Route::post('/MassAction/mail','ContactController@mail_editor');
	Route::post('/SetSlots', 'PatientListController@set_slots');
	// Route::post('/SaveProfile','PatientController@save_profile');

	// patient profile: routes for post requests
	Route::group(['prefix' => '/patient/{patient}'], function () {
		Route::post('therapist', 'PatientController@save_therapist');
		Route::post('day_of_week', 'PatientController@save_day_of_week');
		Route::post('date_from_clinics', 'PatientController@save_date_from_clinics');
		Route::post('password', 'PatientController@save_password');
		Route::post('personal_information', 'PatientController@save_personal_information');
		Route::get('cancel_intervention', 'PatientController@cancel_intervention');
		Route::post('notes', 'PatientController@save_notes_of_therapist');
	});

	Route::get('/patient_list', 'PatientListController@show');
	Route::any('/patient_list/data', 'PatientListController@anyData')->name('datatables.data');

	// Experimental for M3
	Route::get('/Assignment/{patient}/{week}','DiaryController@entry');
	Route::post('/SaveAssignment/{patient}/{week}','DiaryController@save_entry');
});
