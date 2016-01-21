<?php
require_once ('Controller.php');

namespace App\Http\Controllers;



use App\Http\Controllers;
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
	public function home()
	{

		//if (session_info.role == 'therapist') {
		//patient_list = new Patient_list;
		//patient_list.show(session_info.
		//page_definition);
		//} else if (session_info.role ==
		//'patient')
		//{
		// diary.show();
		//} else {Anmelder.enter_system(Cookie);
		//
		//}


	}

}
?>