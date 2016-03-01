<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Auth;



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
	public function cancel_intervention($name)
	{

		//$patient=Patient(name);
		//$patient->patientStatus="P130";
		//Save $patient;
		//Alert('Zusammenarbeit mit Patient '.
		//name.' beendet');
		//return Redirec::to('/diary/'.name);


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
		$thisPatient = \App\Patient::where('name', $name)->first();
		$patient_info = new PatientInfo($thisPatient);
		$profile_user_model=[];
		$profile_user_model['Name']=$name;
		$profile_user_model['Role']=$user_role;
		$Patient=[];
		$patient['assignment_day']=$patient_info->assignment_day();
		$patient['assignmentDayChagesLeft']=$patient_info->assignmentDayChangesLeft();
		$patient['code']=$patient_info->code();
		$patient['dateFromClinics']=$patient_info->dateFromClinics();
		$patient['lastActivity']=$patient_info->lastActivity();
		$patient['notes']=$patient_info->notes();
		$patient['patientWeek']=$patient_info->patientWeek();
		$patient['personalInformation']=$patient_info->personalInformation();
		$patient['status']=$patient_info->status();
		$patient['therapist']=$patient_info->therapist();
		$profile_user_model['Patient']=$patient;
		// $profile_user_model['Patient']=Patient($name);
		// return dd($profile_user_model);
		return view('patient.patient_profile')-> with($profile_user_model);
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

}
?>