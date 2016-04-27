<?php
namespace App\Http\Controllers;

use App\Models;

use App\Models\UserRole;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:30
 */
class AuxController extends Controller {

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
		$request->session()->reflash(); // Keep alerts

		switch ($request->user()->type) {
			case UserRole::PATIENT:
				return Redirect::to('/Diary');
			case UserRole::ADMIN:
				return Redirect::to('/admin_home');
			case UserRole::THERAPIST:
				return Redirect::to('/patient_list');
		}
	}

}
?>