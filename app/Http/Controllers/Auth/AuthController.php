<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Controllers\Days;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    |  Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the the authentication of existing users.
    */

    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     */
    protected $redirectTo = '/Home';

    /**
     * Show login page when user logged out.
     */
    protected $redirectAfterLogout = "/Login";

    /**
     * The view containing the login form.
     */
    protected $loginView = 'gate.start_page';

    /**
     * Check the name when authenticating a user.
     *
     * The value is equal to the column name (of the users table) and
     * the name of the field in the login form.
     */
    protected $username = 'name';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application login form - overwrite the default implementation and tell
     * the view if the registration process is available.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('gate.start_page', ['RegistrationPossible' => (new Days())->day_available()]);
    }
}
