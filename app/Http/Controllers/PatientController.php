<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Patient;
use App\Therapist;
use App\Helper;

use Carbon\Carbon;

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

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Bricht die Intervention für den Patienten mit dem angegebenen Benutzernamen ab,
	 *
	 * @param name
	 */
	public function cancel_intervention(Request $request)
	{
		$name=$request->input('name');
		//$patient=Patient(name);
		//$patient->patientStatus="P130";
		//Save $patient;
		//Alert('Zusammenarbeit mit Patient '.
		//name.' beendet');
		return Redirect::to('/Home');


	}

	/**
	 * Zeigt die Profilseite des Patienten mit dem angegebenen Benutzernamen an. Ist
	 * name nicht angegeben, so muss die Rolle des benutzers 'patient' sein und es
	 * wird die Profilseite des aktuellen Patienten angezeigt.
	 *
	 * @param name
	 */
	public function profile(Request $request,$name=NULL)
	{
	//	return dd($request->user());
		if (! $name) {
			$name=Auth::user()->name;
		}
		$user_role=$request->user()->type;
		if ($user_role == 'patient' &&  $name!=Auth::user()-> name) {
			return	Redirect::to('/');
		}
		//$patient=Patient(name);
		$patient = Patient::where('name', $name)->first();
		$profile_user_model=[];
		$profile_user_model['Name']=$name;
		$profile_user_model['Role']=$user_role;
		$patient_info=[];
		$patient_info['assignment_day']=Helper::generate_day_number_map()[$patient->assignment_day];
		$patient_info['assignmentDayChagesLeft']=$patient->assignment_day_changes_left;
		$patient_info['code']=$patient->code;
		$patient_info['dateFromClinics']=$patient->date_from_clinics;
		$patient_info['lastActivity']=$patient->last_activity;
		$patient_info['notes']=$patient->notes_of_therapist;
		$patient_info['patientWeek']=$patient->patient_week();
		$patient_info['personalInformation']=$patient->personal_information;
		$patient_info['status']=$patient->status();
		$patient_info['therapist']=$patient->therapist === null ? "-" : $patient->therapist->name;
		$patient_info['listOfTherapists']=array_pluck(Therapist::all()->sortBy('name')->toArray(),'name');
		$profile_user_model['Patient']=$patient_info;
		// $profile_user_model['Patient']=Patient($name);
		// return dd($profile_user_model);
		return view('patient.patient_profile')->with($profile_user_model);
	}

	/**
	 * Das Profil wird mit den geänderten Daten aktualisiert. Bei Passwortänderung
	 * wird vorher geprüft ob das alte Passwort korrekt ist. Anschließend wird zur
	 * Homepage des Benutzers weitergeleitet.
	 *
	 * @param name
	 * @param date_from_clinics
	 * @param new_password
	 * @param notes
	 * @param old_password
	 * @param personal_information
	 * @param therapist
	 */
	public function save_profile(Request $request)
	{
		$name=$request->input('name');
		$date_from_clinics=$request->input('dateFromClinics');
		$new_password=$request->input('newPassword');
		$notes=$request->input('notes');
		$old_password=$request->input('oldPassword');
		$personal_information=$request->input('personalInformation');
		$therapist=$request->input('therapist');

		//$patient=Patient(name);
		//if ($request->'oldPassword' != Password
		//of name) {
		// alert('Falsches Passwort.');
		// return view('patient.patient_profile')-
		//>where('UserName'=>$name,
		//'Patient'=>$patient);
		//}
		//foreach ($par as args) {
		//this.change_in_profile($patient,$par,
		//$request[$par]);
		//}
		//save $patient to database;
		//alert("Profil für ".$name."
		//aktualisiert.");
		Redirect::to('/Home');
	}

	public function save_therapist(Request $request, Patient $patient) {
		$name_of_therapist = $request->input('therapist');

		$therapist = Therapist::where('name', $name_of_therapist)->first();

		if ($therapist === null && $patient->therapist() !== null) {
			$patient->therapist()->dissociate();

			Alert::warning('Ein Therapeut mit dem Namen '. $name_of_therapist .
							' ist nicht registriert. Der Therapeut wurde somit zurückgesetzt.')->flash();
		} else {
			$patient->therapist()->associate($therapist);

			Alert::info('Der Therapeut wurde erfolgreich geändert.')->flash();
		}

		$patient->save();

		return Redirect::back();
	}

	public function save_day_of_week(Request $request, Patient $patient) {
		// Sonntag, ..., Donnerstag
		$day_of_week = $request->input('day_of_week');

		$day_number = Helper::generate_day_name_map()[$day_of_week];

		if ($day_number !== null) {
			$patient->assignment_day = $day_number;
			$patient->save();

			Alert::info('Der Schreibtag wurde erfolgreich geändert.')->flash();

			return Redirect::back();
		} else {
			Alert:error('Der angegebene Schreibtag ist ungültig.')->flash();

			return Redirect::back();
		}
	}

	public function save_date_from_clinics(Request $request, Patient $patient) {
		// format: yyyy-mm-dd
		$date_from_clinics_string = $request->input('dateFromClinics');

		$date_from_clinics = Carbon::createFromFormat('Y-m-d', $date_from_clinics_string);

		if ($date_from_clinics !== null) {
			$patient->date_from_clinics = $date_from_clinics;

			Alert::info('Das Entlassungsdatum wurde erfolgreich geändert.')->flash();
		} else {
			Alert::error('Das Format des angegebenen Entlassungsdatums ist unbekannt.')->flash();
		}

		return Redirect::back();
	}

	public function save_password(Request $request, Patient $patient) {
		$old_password = $request->input('oldPassword');
		$password = $request->input('newPassword');

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
		$personal_information = $request->input('personalInformation');

		if ($personal_information !== '') {
			$patient->personal_information = $personal_information;
			$patient->save();

			Alert::info('Die persönlichen Notizen wurden erfolgreich geändert.')->flash();
		} else {
			Alert::danger('Bitte geben Sie die zu speichernden Notizen an.')->flash();
		}

		return Redirect::back();
	}

}
?>
