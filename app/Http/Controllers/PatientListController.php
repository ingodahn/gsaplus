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
use App\Models\PatientStatus;
use App\Models\AssignmentStatus;

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

		$days = new Days;
		$days->set_days($Days);
		$Days1 = $days->get_days();

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
		// Slots von Days,
		// Patientenliste von datatable
		$days = new Days;
		$Slots = $days->get_days();

		$params['Slots'] = $Slots;

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
	 * Woche (patientWeek) - Woche der Intervention (0...13)
	 * Tagebuchtag (assignment_day) - gewählter Schreibtag
	 * Status (status): Status des Patienten P010...P130, idealerweise als Text, evtl nur als Kürzel P.... als Zeichenkette sortierbar nach P... .
	 * Überfällig (overdue) - Wert der Form "<Anzahl der überfälligen Einträge>/<Aktuelle Wochennr. = Anzahl der bereits gestellten Aufgaben>" Sortierbar nach numerischem Wert dieses bruches
	 * Zuletzt aktiv (lastActivity) - Datum des letzten Zugriffs auf eine Seite des Systems außer der Startseite
	 * Therapeut (therapist) - Benutzername des Therapeuten oder leer
	 *
	 */
	public function anyData()
	{
		$days_map = Helper::generate_day_number_map();

		return Datatables::of(Patient::select('*'))
			->addColumn('patient_status', function ($patient) {
				$status = $patient->status();

				return $status.': '.PatientStatus::$STATUS_INFO[$status];
			})
			->addColumn('status_of_next_assignment', function($patient){
				$status = $patient->status_of_next_assignment();

				return $status.': '.AssignmentStatus::$STATUS_INFO[$status];
			})
			->edit_column('assignment_day', function($row) use ($days_map) {
				return $days_map[$row->assignment_day];
			})
			->addColumn('patient_week', function($patient) {
				return $patient->patient_week() === -1 ? "-" : $patient->patient_week();
			})
			->addColumn('last_activity', function($patient) {
				return $patient->last_activity != null ? $patient->last_activity->format('d.m.Y') : "";
			})
			->addColumn('therapist', function($patient) {
				return $patient->therapist !== null ? $patient->therapist->name : "-";
			})
			-> addColumn('selection', function($row){
				$name=$row->name;
				return '<input type="checkbox" name="list_of_names[]" value="'.$name.'"></input>';
			})
			->addColumn('overdue', function($patient) {
				return round($patient->overdue() * 100, 0)."%";
			})
			->edit_column('name', function($patient) {
				$name = $patient->name;
				return '<a href="/Diary/'.$name.'">'.$name.'</a>';
			})
			->removeColumn('id')
			->removeColumn('created_at')
			->removeColumn('updated_at')
			->removeColumn('is_random')
			->removeColumn('personal_information')
			->make(true);
	}

}
?>
