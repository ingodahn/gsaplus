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

Route::get('/', function () {
    Alert::danger('Errrorororororo')->flash();
    Alert::success('You have successfully created an alert! It even has an obnoxiosly long message.')->flash();
    Alert::warning('Better check yourself befor you shrek yourself.')->flash();
    Alert::info('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')->flash();
    return view('welcome');
});

Route::get('/register', function () {
    return view('register-greeting');
});

Route::get('/register/greeting', function () {
    return view('register-greeting');
});

Route::get('/register/commit', function () {
    return view('register-commit');
});

Route::get('/register/data', function () {
    return view('register-data');
});

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
