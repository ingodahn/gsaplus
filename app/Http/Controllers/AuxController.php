<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models;
use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;
use Session;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Days;

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
	 * Rücksprung zur Homepage des Benutzers. Dies ist
	 * <ul>
	 * 	<li>für angemeldete Patienten die Seite mit der Tagebuchübersicht</li>
	 * 	<li>für angemeldete Therapeuten die Seite patient_list</li>
	 * 	<li>für angemeldete Administratoren ???</li>
	 * 	<li>für alle anderen die Basis-URL /, also die jeweilige Startseite</li>
	 * </ul>
	 * Dazu muss die Rolle des Benutzers im Objekt user in Verbindung mit dem Session
	 * key auf dem Server gespeichert werden.
	 */
	public function home(Request $request)
	{
		switch (get_class($request->user()->userable)) {
			case 'App\Patient':
				return view('patient.diary')-> with('name',Auth::user()-> name);
			case 'App\Admin':
				return view('admin.home');
			case 'App\Therapist':
				$days = new Days;
				$patientListModel = array();
				$patientListModel['Slots'] = $days->get_days();
				$patientListModel['PatientList']="<p><a href=\"/Diary/test-p\">test-p</a></p>";
				return view('therapist.patient_list')->with($patientListModel);
		}
	}

}
?>