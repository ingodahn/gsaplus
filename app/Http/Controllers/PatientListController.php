<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;

use App\Http\Requests;
use Yajra\Datatables\Datatables;

use App\Models;
use App\Helper;
use App\Patient;

use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;



/**
 * Diese Klasse implementiert alle Aktionen, die der Therapeut auf der
 * Patientenliste vornehmen kann
 * <ul>
 * 	<li>Freie Slots setzen,</li>
 * 	<li> Liste sortieren und </li>
 * 	<li>filtern.</li>
 * </ul>
 * page_definition enth�lt die Beschreibung der aktuellen Sortier- und
 * Filterparameter
 * @author dahn
 * @version 1.0
 * @created 26-Jan-2016 17:11:36
 */
class PatientListController extends Controller
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Days wird im System modifiziert und die Seite mit der Patientenliste (der der
	 * Slots-Teil davon) wird neu aufgebaut
	 *
	 * @param days
	 */
	public function set_slots(Request $request)
	{
		$Days = ['Sonntag' => $request->input('So_slots'),
			'Montag' => $request->input('Mo_slots'),
			'Dienstag' => $request->input('Di_slots'),
			'Mittwoch' => $request->input('Mi_slots'),
			'Donnerstag' => $request->input('Do_slots')
		];

		$days=new Days;
		$days->set_days($Days);
		$Days1=$days->get_days();
		return dd($Days1);

	}


	/**
	 * 'Liefere Seite patient_list'(
	 * <ul>
	 * 	<li>'Slots'(Days)</li>
	 * 	<li>patients(</li>
	 * </ul>
	 * )
	 * Tabelle patient_list von datatable
	 *
	 */
	 public function show(Request $request) {
		//Zeige Seite patient_list mit
		$days=new Days;
		$Slots = $days->get_days();
		// Slots von Days,
		// Patientenliste von datatable
		
		$params['Slots']=$Slots;
		return view('therapist.patient_list')->with($params);


	}

	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * Die folgenden Spalten werden benötigt:
	 * Auswahl - checkbox für die Auswahl von Massenaktionen (Mail)
	 * Name (name): Benutzername des Patienten - Link zu Diary/{name}
	 * Code (code) - Code des Patienten
	 * Status (status): Status des Patienten P010...P130, idealerweise als Text, evtl nur als Kürzel P.... als Zeichenkette sortierbar nach P... .
	 * Überfällig (overdue) - Wert der Form "<Anzahl der überfälligen Einträge>/<Aktuelle Wochennr. = Anzahl der bereits gestellten Aufgaben>" Sortierbar nach numerischem Wert dieses bruches
	 * Zuletzt aktiv (last_activity) - Datum des letzten Zugriffs auf eine Seite des Systems außer der Startseite
	 * Therapeut (therapist) - Benutzername des Therapeuten oder leer
	 *
	 */
	public function anyData()
	{
		$days_map = Helper::generate_day_number_map();

		return Datatables::of(Patient::select('*'))
			->addColumn('overdue', function ($patient) {
				if ($patient->assignments()->get()->last() !== null
						&& $patient->assignments()->get()->last()->state === 0) {
					return "ja";
				} else {
					return "nein";
				}
			})
			->edit_column('assignment_day', function($row) use ($days_map) {
				return $days_map[$row->assignment_day];
			})
			->make(true);
	}

}
?>
