<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
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
use UxWeb\SweetAlert\SweetAlert as Alert;



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
		$Slots = $days->get_days();

		$params['Slots'] = $Slots;
		Alert::info('Die Zahl der freien Slots wurde aktualisiert')->flash();

		return view('therapist.patient_list')->with($params);

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

		$infos = new Collection;
		$patients = Patient::all();

		foreach ($patients as $patient) {
			$infos->push($patient->info_with('therapist'));
		}

		return Datatables::of($infos)
				->addColumn('selection', function($patient_info){
					return '<input type="checkbox" name="list_of_names[]" value="'.$patient_info['name'].'"></input>';
				})
				->editColumn('overdue', function($patient_info) {
					return round($patient_info['overdue'] * 100, 0)."%";
				})
				->edit_column('name', function($patient_info) {
					return '<a href="/Diary/'.$patient_info['name'].'">'.$patient_info['name'].'</a>';
				})
				->editColumn('status', function ($patient_info) {
					return $patient_info['patientStatus'].': '.PatientStatus::$STATUS_INFO[$patient_info['patientStatus']];
				})
				->addColumn('therapist', function ($patient_info) {
					return array_get($patient_info, 'therapist.name', '-');
				})
				->editColumn('statusOfNextAssignment', function($patient_info){
					return $patient_info['statusOfNextAssignment'].': '
							.AssignmentStatus::$STATUS_INFO[$patient_info['statusOfNextAssignment']];
				})
				->editColumn('patientWeek', function($patient_info) {
					return $patient_info['patientWeek'] === -1 ? "-" : $patient_info['patientWeek'];
				})
				->edit_column('assignmentDay', function($patient_info) use ($days_map) {
					return $days_map[$patient_info['assignmentDay']];
				})
				->edit_column('lastActivity', function($patient_info) {
					return array_get($patient_info, 'lastActivity', '-');
				})
				->removeColumn('id')
				->removeColumn('createdAt')
				->removeColumn('updatedAt')
				->removeColumn('isRandom')
				->removeColumn('personalInformation')
				->removeColumn('email')
				->make(true);
	}

}
?>
