<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models;
use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;

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
		switch ($request->user()->type) {
			case 'patient':
				return view('patient.diary');
			case 'admin':
				return view('admin.home');
			case 'therapist':
				$days = new Days;
				$patientListModel = array();
				$patientListModel['Slots'] = $days->get_days();

				return view('therapist.patient_list')->with($patientListModel);
		}
	}

}
?>