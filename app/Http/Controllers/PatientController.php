<?php
namespace App\Http\Controllers;

use App\Models\PatientStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Patient;
use App\Therapist;
use App\Helper;

use App\Models\UserRole;

use Jenssegers\Date\Date;

use Hash;

use Prologue\Alerts\Facades\Alert;

use Illuminate\Support\Facades\Redirect;

/**
 * Die Klasse zeigt das Profil des Patienten an und erlaubt Veränderungen daran.
 * @author dahn
 * @version 1.0
 * @created 12-Feb-2016 23:45:09
 */
class PatientController extends Controller
{

	/**
	 * Bricht die Intervention für den Patienten mit dem angegebenen Benutzernamen ab,
	 *
	 * @param name
	 */
	public function cancel_intervention(Request $request, Patient $patient)
	{
		$patient->intervention_ended_on = Date::now();
		$patient->save();

		Alert::info('Zusammenarbeit mit Patient '.$patient->name.' wurde beendet.')->flash();

		return Redirect::back();
	}

	/**
	 * Zeigt die Profilseite des Patienten mit dem angegebenen Benutzernamen an. Ist
	 * name nicht angegeben, so muss die Rolle des benutzers 'patient' sein und es
	 * wird die Profilseite des aktuellen Patienten angezeigt.
	 *
	 * @param name
	 */
	public function profile(Request $request, $name = null)
	{
		//	return dd($request->user());
		if (!$name) {
			$name = Auth::user()->name;
		}

		$user_role = $request->user()->type;

		if ($user_role == UserRole::PATIENT && $name != Auth::user()->name) {
			return Redirect::to('/');
		}

		$days = new Days;

		$patient = Patient::whereName($name)->firstOrFail();

		$patient_info = $patient->to_info();

		switch ($user_role) {
			case UserRole::PATIENT:
				$status = PatientStatus::$STATUS_INFO[$patient_info['status']];
				break;
			case UserRole::THERAPIST:
			case UserRole::ADMIN:
				$status = $patient_info['status'].': '.PatientStatus::$STATUS_INFO[$patient_info['status']];
				break;
		}

		$patient_info['assignmentDay'] = Helper::generate_day_number_map()[$patient_info['assignmentDay']];
		$patient_info['availableDays'] = $days->get_available_days();
		$patient_info['status'] = $status;
		$patient_info['listOfTherapists'] = array_pluck(Therapist::all()->sortBy('name')->toArray(), 'name');

		$profile_user_model=[];
		$profile_user_model['Patient'] = $patient_info;

		// $profile_user_model['Patient']=Patient($name);
		// return dd($profile_user_model);
		return view('patient.patient_profile')->with($profile_user_model);
	}

	public function save_therapist(Request $request, Patient $patient) {
		$name_of_therapist = $request->input('therapist');

		$therapist = Therapist::whereName($name_of_therapist)->first();

		if ($therapist === null) {
			$message_to_user = 'Ein Therapeut mit dem Namen '. $name_of_therapist .
				' ist nicht registriert.';

			if ($patient->therapist() !== null) {
				$patient->therapist()->dissociate();

				$message_to_user.' Der Therapeut wurde somit zurückgesetzt.';
			}

			Alert::warning($message_to_user)->flash();
		} else {
			$patient->therapist()->associate($therapist);

			Alert::info('Der Therapeut wurde erfolgreich geändert.')->flash();
		}

		$patient->save();

		return Redirect::back();
	}

	public function save_day_of_week(Request $request, Patient $patient) {
		$is_therapist = ($request->user()->type === UserRole::THERAPIST);

		if ($patient->assignment_day_changes_left > 0 || $is_therapist) {
			// Sonntag, ..., Donnerstag
			$day_of_week = $request->input('day_of_week');

			$day_number = Helper::generate_day_name_map()[$day_of_week];

			if ($day_number !== null) {
				$patient->assignment_day = $day_number;
				$is_therapist ?: $patient->assignment_day_changes_left -= 1 ;
				$patient->save();

				Alert::info('Der Schreibtag wurde erfolgreich geändert.')->flash();
			} else {
				Alert::danger('Der angegebene Schreibtag ist ungültig.')->flash();
			}
		} else {
			Alert::danger("Leider ist die Änderung des Schreibtages nicht mehr möglich.")->flash();
		}

		return Redirect::back();
	}

	public function save_date_from_clinics(Request $request, Patient $patient) {
		// format: dd.mm.yyyy
		$date_from_clinics_string = $request->input('date_from_clinics');

		try {
			$date_from_clinics = Date::createFromFormat('d.m.Y', $date_from_clinics_string);
		} catch (\InvalidArgumentException $e) {
			Alert::danger('Das Format des angegebenen Entlassungsdatums ist unbekannt.')->flash();
		}

		if (isset($date_from_clinics)) {
			$patient->date_from_clinics = $date_from_clinics;
			$patient->save();

			Alert::info('Das Entlassungsdatum wurde erfolgreich geändert.')->flash();
		}

		return Redirect::back();
	}

	public function save_password(Request $request, Patient $patient) {
		$old_password = $request->input('old_password');
		$password = $request->input('new_password');

		if (Hash::check($old_password, $patient->password)) {
			$patient->password = bcrypt($password);
			$patient->save();

			Alert::info('Das Passwort wurde erfolgreich geändert.')->flash();
		} else {
			Alert::danger('Das eingegebene Passwort ist nicht korrekt.')->flash();
		}

		return Redirect::back();
	}

	public function save_personal_information(Request $request, Patient $patient) {
		$personal_information = $request->input('personal_information');

		if ($personal_information !== '') {
			$patient->personal_information = $personal_information;
			$patient->save();

			Alert::info('Die persönlichen Notizen wurden erfolgreich geändert.')->flash();
		} else {
			Alert::danger('Bitte geben Sie die zu speichernden Notizen an.')->flash();
		}

		return Redirect::back();
	}

	public function save_notes_of_therapist(Request $request, Patient $patient) {
		$notes_of_therapist = $request->input('notes');

		if ($notes_of_therapist !== '') {
			$patient->notes_of_therapist = $notes_of_therapist;
			$patient->save();

			Alert::info('Die Notizen wurden erfolgreich geändert.')->flash();
		} else {
			Alert::danger('Bitte geben Sie die zu speichernden Notizen an.')->flash();
		}

		return Redirect::back();
	}

}
?>
