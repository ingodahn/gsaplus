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
			if (Patient::whereCode($code->value)->exists()) {
				$patient = Patient::whereCode($code->value)->firstOrFail();
				$codes[$code->value] = $patient->name;
			} else {
				$codes[$code->value] = null;
			}
		}

		return view("admin.codes")->with(["codes" => $codes]);
	}

	/**
	 * Zeigt die Liste aller Benutzer mit ihrer Rolle
	 */
	public function admin_users()
	{
		$info = [];

		foreach (Patient::all() as $patient) {
			$info[$patient->name]['Code'] = $patient->code;
			$info[$patient->name]['Tagebuchtag'] = $patient->assignment_day;
			$info[$patient->name]['Änderungen möglich'] = $patient->assignment_day_changes_left;

			if ($patient->therapist !== null) {
				$info[$patient->name]['Therapeut'] = $patient->therapist->name;
			}
		}

		dd($info);
	}

}
?>
