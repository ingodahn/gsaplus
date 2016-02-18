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
		if (! $name) {
			$name=Auth::user()-> name;
		}
		// if (role of current user is patient && ! $name==Auth::user()-> name) {
		//	Redirect::to('/');
		// }
		//$patient=Patient(name);
		$profile_user_model=[];
		$profile_user_model['Name']=$name;
		$profile_user_model['Role']=get_class($request->user()->userable);
		// $profile_user_model['Patient']=Patient($name);
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
	 * @param personal_information
	 * @param therapist
	 */
	public function save_profile($name, $date_from_clinics, $new_password, $notes, $personal_information, $therapist)
	{

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
		//Redirect::to('/Home');



	}

}
?>