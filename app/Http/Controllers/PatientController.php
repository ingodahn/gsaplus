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

use UxWeb\SweetAlert\SweetAlert as Alert;

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
	 * function cancel_intervention
	 * Bricht die Intervention für den Patienten mit dem angegebenen Benutzernamen ab,
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */
	public function cancel_intervention(Request $request, Patient $patient)
	{
		if ($request->user()->type !== UserRole::THERAPIST) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$patient->intervention_ended_on = Date::now();
		$patient->save();

		Alert::success('Zusammenarbeit mit Patient '.$patient->name.' wurde beendet.')->persistent();

		return Redirect::back();
	}

	/** 
	 * public function profile
	 * @param Request $request
	 * @param null $name
	 * @return $this
	 *
	 * Zeigt die Profilseite des Patienten mit dem angegebenen Benutzernamen an. Ist
	 * name nicht angegeben, so muss die Rolle des benutzers 'patient' sein und es
	 * wird die Profilseite des aktuellen Patienten angezeigt.
	 */
	public function profile(Request $request, $name = null)
	{
		//	return dd($request->user());
		if (!$name) {
			$name = Auth::user()->name;
		}

		$user_role = $request->user()->type;

		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}

		$days = new Days;

		$patient = Patient::whereName($name)->firstOrFail();

		$patient_info = $patient->info_with('therapist');

		switch ($user_role) {
			case UserRole::PATIENT:
				$status = PatientStatus::$STATUS_INFO[$patient_info['patientStatus']];
				break;
			case UserRole::THERAPIST:
			case UserRole::ADMIN:
				$status = $patient_info['patientStatus'].': '.PatientStatus::$STATUS_INFO[$patient_info['patientStatus']];
				break;
		}

		$patient_info['assignmentDay'] = Helper::generate_day_number_map()[$patient_info['assignmentDay']];
		$patient_info['availableDays'] = $days->get_available_days();
		$patient_info['status'] = $status;
		$patient_info['therapist'] = array_get($patient_info, 'therapist.name', '-');
		$patient_info['listOfTherapists'] = array_pluck(Therapist::all()->sortBy('name')->toArray(), 'name');
		$patient_info['dateFromClinics'] = array_get($patient_info, 'dateFromClinics', '-');

		$profile_user_model=[];
		$profile_user_model['Patient'] = $patient_info;

		return view('patient.patient_profile')->with($profile_user_model);
	}

	/**
	 * function save_therapist
	 * Ordnet den angegebenen Therapeuten dem angegebenen Patienten zu
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */
	public function save_therapist(Request $request, Patient $patient) {
		$name=$patient->name;
		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok || $request->user()->type !== UserRole::THERAPIST) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$name_of_therapist = $request->input('therapist');

		$therapist = Therapist::whereName($name_of_therapist)->first();

		if ($therapist === null) {
			$message_to_user = 'Ein Therapeut mit dem Namen '. $name_of_therapist .
				' ist nicht registriert.';

			if ($patient->therapist() !== null) {
				$patient->therapist()->dissociate();

				$message_to_user.' Der Therapeut wurde somit zurückgesetzt.';
			}

			Alert::warning($message_to_user)->persistent();
		} else {
			$patient->therapist()->associate($therapist);

			Alert::success('Der Therapeut wurde erfolgreich geändert.')->persistent();
		}

		$patient->save();

		return Redirect::back();
	}

	/**
	 * function save_day_of_week
	 * Setzt für den Patienten $patient den Schreibtag entsprechend Input-Parameter day_of_week
	 * Patienten können dies nur tun, wenn die Zahl der erlaubten Änderungen (1) nicht erreicht ist.
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */
	public function save_day_of_week(Request $request, Patient $patient) {
		$name=$patient->name;
		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$is_therapist = ($request->user()->type === UserRole::THERAPIST);

		if ($patient->assignment_day_changes_left > 0 || $is_therapist) {
			// Sonntag, ..., Donnerstag
			$day_of_week = $request->input('day_of_week');

			$day_number = Helper::generate_day_name_map()[$day_of_week];

			if ($day_number !== null) {
				$patient->assignment_day = $day_number;
				$is_therapist ?: $patient->assignment_day_changes_left -= 1 ;
				$patient->save();

				Alert::success('Der Schreibtag wurde erfolgreich geändert.')->persistent();
			} else {
				Alert::error('Der angegebene Schreibtag ist ungültig.')->persistent();
			}
		} else {
			Alert::error("Leider ist die Änderung des Schreibtages nicht mehr möglich.")->persistent();
		}

		return Redirect::back();
	}

	/**
	 * function save_date_from_clinics
	 * setzt das Entlassungsdatum für $patient entsprechend Input-Parameteer date_from_clinics
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */

	public function save_date_from_clinics(Request $request, Patient $patient) {
		if ($request->user()->type !== UserRole::THERAPIST){
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		// format: dd.mm.yyyy
		$date_from_clinics_string = $request->input('date_from_clinics');

		try {
			$date_from_clinics = Date::createFromFormat('d.m.Y', $date_from_clinics_string);
		} catch (\InvalidArgumentException $e) {
			Alert::warning('Das Format des angegebenen Entlassungsdatums ist unbekannt.')->persistent();
		}

		if (isset($date_from_clinics)) {
			$patient->date_from_clinics = $date_from_clinics;
			$patient->save();

			Alert::success('Das Entlassungsdatum wurde erfolgreich geändert.')->persistent();
		}

		return Redirect::back();
	}

	/**
	 * function save_password
	 * Setzt das neue Passowrt 'new_password' wenn das alte Password 'old_password' korrekt war
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */
	public function save_password(Request $request, Patient $patient) {
		$name=$patient->name;
		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok || $request->user()->type !== UserRole::PATIENT) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$old_password = $request->input('old_password');
		$password = $request->input('new_password');

		if (Hash::check($old_password, $patient->password)) {
			$patient->password = bcrypt($password);
			$patient->save();

			Alert::success('Das Passwort wurde erfolgreich geändert.')->persistent();
		} else {
			Alert::error('Das eingegebene Passwort ist nicht korrekt.')->persistent();
		}

		return Redirect::back();
	}

	/**
	 * function save_personal_information
	 * Ändern der persönlichen Informationen durch den Patienten
	 * @param Request $request
	 * @param Patient $patient
	 * @return mixed
	 */
	public function save_personal_information(Request $request, Patient $patient) {
		$name=$patient->name;
		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok || $request->user()->type !== UserRole::PATIENT) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$personal_information = $request->input('personal_information');

		if ($personal_information !== '') {
			$patient->personal_information = $personal_information;
			$patient->save();

			Alert::success('Ihre persönlichen Informationen wurden erfolgreich gespeichert.')->persistent();
		} else {
			Alert::warning('Bitte geben Sie die zu speichernden Notizen an.')->persistent();
		}

		return Redirect::back();
	}

	public function save_notes_of_therapist(Request $request, Patient $patient) {
		$name=$patient->name;
		//Access control
		$ok=GateController::check_access($request, $name);
		if (! $ok) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$notes_of_therapist = $request->input('notes');

		if ($notes_of_therapist !== '') {
			$patient->notes_of_therapist = $notes_of_therapist;
			$patient->save();

			Alert::success('Die Notizen wurden erfolgreich geändert.')->persistent();
		} else {
			Alert::warning('Bitte geben Sie die zu speichernden Notizen an.')->persistent();
		}

		return Redirect::back();
	}

}
?>
