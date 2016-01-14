<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
    // return view('welcome');
// });

Route::get('/', 'GateController@enter_system');
//Route::get('/', 'GateController@enter_system(Cookie $cookie)');
Route::post('/StartRegistration', 'GateController@start_registration');
Route::get('/ResetPassword', 'GateController@reset_password');
Route::post('/CheckLoginPassword', 'GateController@check_login_password');
Route::get('/ContactTeam', 'ContactController@contact_team');
Route::post('/SendMessage', 'ContactController@send_message');
Route::post('/MailForPassword', 'GateController@mail_for_password');
Route::get('/Home', 'AuxController@home');
Route::get('/FromWelcome', 'GateController@from_welcome');
Route::get('/Accepted', 'GateController@req_patient_data');
Route::post('/SavePatientData', 'GateController@save_patient_data');
Route::get('/GetResetCode', 'GateController@get_reset_code');

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
    //
});
