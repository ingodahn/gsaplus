<?php


namespace App\Http\Controllers;

use App\Code;
use App\Helper;
use App\Patient;
use App\Therapist;
use App\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use UxWeb\SweetAlert\SweetAlert as Alert;

/**
 * @author dahn
 * @version 1.0
 * @created 02-Feb-2016 04:08:29
 */
class AdminController extends Controller
{
	
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

		$day_map = Helper::generate_day_number_map();

		foreach (Patient::all() as $patient) {
			$info[$patient->name]['Code'] = $patient->code;
			$info[$patient->name]['Schreibtag'] = $day_map[$patient->assignment_day];
			$info[$patient->name]['Änderungen möglich'] = $patient->assignment_day_changes_left;

			if ($patient->therapist !== null) {
				$info[$patient->name]['Therapeut'] = $patient->therapist->name;
			}
		}

		return view("admin.patients")->with('info', $info);
	}

	public function admin_home() {
		$infos = ['therapist' => new Collection, 'patient' => new Collection, 'admin' => new Collection];

		foreach (User::all() as $user) {
			$infos[$user->type]->push($user->info());
		}

		foreach ($infos as $key => $info) {
			$infos[$key] = $info->sortBy('name');
		}

		return view('admin.home')->with('infos', $infos);
	}

	public function create_therapist(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => array('Regex:/^[a-zA-Z0-9\.\-_]+$/', 'required'),
			'email' => 'required|email'
		]);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}

		$name = $request->input('name');
		$password = $request->input('password');
		$email = $request->input('email');

		$emailExists = User::whereEmail($email)->exists();
		$nameExists = User::whereName($name)->exists();

		//if (Name or eMail already in use) {
		if ($nameExists || $emailExists) {
			$message = "Der Therapeut konnte leider nicht angelegt werden. ".
				($nameExists ? "Bitte wählen Sie einen anderen Benutzernamen." :
					"Bitte überprüfen Sie die eingegebene E-Mail-Adresse.");

			Alert::error($message, 'Angaben nicht eindeutig')->persistent();
		} else {
			$therapist = new Therapist;
			$therapist->name = $name;
			$therapist->email = $email;
			$therapist->password = bcrypt($password);
			$therapist->is_random = false;

			$therapist->save();

			Alert::success('Der Benutzer wurde erfolgreich angelegt.')->persistent();
		}

		return Redirect::back();
	}

}
?>
