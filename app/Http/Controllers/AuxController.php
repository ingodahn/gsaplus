<?php
namespace App\Http\Controllers;

require_once ('Controller.php');
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models;
use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;

/**
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:30
 */
class AuxController extends Controller

{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * R�cksprung zur Homepage des Benutzers. Dies ist
	 * <ul>
	 * 	<li>f�r angemeldete Patienten die Seite mit der Tagebuch�bersicht</li>
	 * 	<li>f�r angemeldete Therapeuten die Seite patient_list</li>
	 * 	<li>f�r angemeldete Administratoren ???</li>
	 * 	<li>f�r alle anderen die Basis-URL /, also die jeweilige Startseite</li>
	 * </ul>
	 * Dazu muss die Rolle des Benutzers im Objekt user in Verbindung mit dem Session
	 * key auf dem Server gespeichert werden.
	 */
	public function home(Request $request)
	{
		$days = new Days;
		//if (Session::get('Role') == 'therapist') {
		//	$PatientListModel = array();
		//	$PatientListModel['Slots'] = $days->get_days();
		// 	return view('therapist.patient_list)->with($PatientListModel);
		//patient_list.show(session_info.
		//page_definition);
		//} else if (Session::get('Role') ==
		//'patient')
		//{
			return view('patient.diary');
		// diary.show();
		//} else {
		if ($days->day_available())  {
			return view('gate.start_page')->with('RegistrationPossible',true);
		// Result: "registrationPossible";
		} else {
		// return "registrationImpossible";
			return view('gate.start_page')->with('RegistrationPossible',false);
		//	return view(gate.login_only);
		//Result:"registrationImpossible";
		}
		//
		//}


	}

}
?>