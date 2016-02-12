<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

use App\Patient;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    // overwrite the default views (auth.passwords. ...)
    protected $linkRequestView = 'gate.passwords.password';

    protected $resetView = 'gate.passwords.reset';

    // redirect to /Home when procedure is complete
    protected $redirectTo = '/Home';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Change: don't send mails if patient status is >= P130
     */
    public function postEmail(Request $request)
    {
        $p = Patient::where('email', $request->input('email'))->first();

        if ($p !== null
                && $p->patient_status !== 'P130'
                && $p->patient_status !== 'P140') {
            return $this->sendResetLinkEmail($request);
        } else {
            return view('gate.passwords.password');
        }
    }

}
