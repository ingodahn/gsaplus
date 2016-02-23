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
			->removeColumn('email')
			->addColumn('patientStatus', function ($patient) {
				$status_info=array(
					"P010"=>"P010: Nicht registriert",
					"P020"=>"P020: Registriert",
					"P025"=>"P025: Entlassungsdatum erfasst",
					"P030"=>"P030: Erste Aufgabe erhalten",
					"P040"=>"P040: Erste Aufgabe bearbeitet",
					"P045"=>"P045: Erste Aufgabe gemahnt",
					"P050"=>"P050: Erste Aufgabe abgeschickt",
					"P060"=>"P060: Erste Aufgabe kommentiert",
					"P065"=>"P065: Erste Aufgabe Kommentar bewertet",
					"P070"=>"P070: Erste Aufgabe versäumt",
					"P075"=>"P075: Aktuelle Folgeaufgabe definiert",
					"P080"=>"P080: Aktuelle Folgeaufgabe erhalten",
					"P090"=>"P090: Aktuelle Folgeaufgabe bearbeitet",
					"P095"=>"P095: Aktuelle Folgeaufgabe gemahnt",
					"P100"=>"P100: Aktuelle Folgeaufgabe abgeschickt",
					"P110"=>"P110: Aktuelle Folgeaufgabe kommentiert",
					"P115"=>"P115: Aktuelle Folgeaufgabe Kommentar bewertet",
					"P120"=>"P120: Aktuelle Folgeaufgabe versäumt",
					"P130"=>"P130: Mitarbeit beendet",
					"P140"=>"P140: Interventionszeit beendet"
				);

				if ($patient->assignments()->get()->last() !== null
					&& $patient->assignments()->get()->last()->state === 0) {
					return $status_info["P070"];
				} else {
					return $status_info["P050"];
				}
			})
			->edit_column('assignment_day', function($row) use ($days_map) {
				return $days_map[$row->assignment_day];
			})
			-> addColumn('patientWeek', function($row) {
				return "0";
			})
			-> addColumn('lastActivity', function($row) {
				return "none";
			})
			-> addColumn('therapist', function($row) {
				if ($row->therapist !== null) {
					return $row->therapist->name;
				} else {
					return "";
				}
			})
			-> addColumn('overdue', function($row) {
				return "0%";
			})
			->edit_column('name', function($row) {
				$name = $row->name;
				return '<a href="/Diary/'.$name.'">'.$name.'</a>';
			})
			->make(true);
	}

}
?>
