<?php


namespace App\Http\Controllers;
use App\Code;
use App\Patient;
use App\Users;

/**
 * @author dahn
 * @version 1.0
 * @created 02-Feb-2016 04:08:29
 */
class AdminController extends Controller
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Zeigt die Liste aller Codes mit ihrem Status (registriert/unregistriert)
	 */
	public function admin_codes()
	{
		$codes = [];

		foreach (Code::all() as $code) {
			if (Patient::where('code', $code->value)->first() != null) {
				$codes[$code->value] = 'registriert';
			} else {
				$codes[$code->value] = 'nicht registriert';
			}
		}

		return dd($codes);
	}

	/**
	 * Zeigt die Liste aller Benutzer mit ihrer Rolle
	 */
	public function admin_users()
	{
	}

}
?>