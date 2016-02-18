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
	 * Liefert Seite mit allen Patienten mit der bisher gew�hlten Sortierung
	 */
	public function delete_filter()
	{

		//page_definition..filter={sort_field:
		//'UserName', filter_relation: 'contains',
		//filter_value=''}
		//this.show(page_definition);


	}

	/**
	 * Holt die page_definition aus der session_info oder setzt eine Default-
	 * page_definition
	 * page_definition = session_info.page_definition oder page_definition = Alle
	 * Patienten sortiert nach UserName
	 */
	private function old_page_definition()
	{

		//var page_definition = new
		//Page_definition;
		//if (session_info.page_definition) {
		// page_definition = session_info.
		//page_definition;
		//} else {
		// page_definitionr= {filter:
		//{filter_field: "UserName",
		//filter_relation: 'contains',
		//filter_value:''}, sort: {sort_field:
		//'UserName', sort_order: 'ascending'}};
		//}
		//return page_definition;


	}

	/**
	 * return table of
	 *  Find all Patients where page_definition.filter_field is in relation
	 * page_definition.filter_relation to page_definition.filter_value sorted by
	 * page_definition.sorted_by
	 *
	 * @param page_definition
	 */
	private function patients(Page_definition $page_definition)
	{
	}

	/**
	 * Modifiziere den gesetzten Filter und zeige die Seite neu an.
	 *
	 * @param filter
	 */
	public function set_filter(Filter $filter)
	{

		//page_definition=this.
		//old_page_definition();
		//page_definition.filter=filter;
		//this.show(page_definition);


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
		//page_definition=this.
		//old_page_definition();
		$days=new Days;
		$days->set_days($Days);
		//this.show(page_definition;
		$Days1=$days->get_days();
		return dd($Days1);

	}

	/**
	 * sort_field wird ersetzt. Wenn sort_field dabei nicht ge�ndert wird, wird
	 * sort_order ge�ndert
	 *
	 * @param sort_field
	 */
	public function set_sort(Sort $sort_field)
	{

		//page_definition=this.
		//old_page_definition();
		//if (page_definition.sort.sort_field ==
		//sort_field) {
		// change page_definition.sort.sort_order;
		//
		//} else {
		// page_definition.sort.sort_field =
		//sort_field;
		// page_definition.sort.sort_order =
		//'ascending';
		//}
		//this.show(page_definition);


	}

	/**
	 * 'Liefere Seite patient_list'(
	 * <ul>
	 * 	<li>'Slots'(Days)</li>
	 * 	<li>'Filter'(page_definition)</li>
	 * 	<li>patients(</li>
	 * </ul>
	 *  this.patients(page_definition)
	 * <ul>
	 * 	<li>page_definition</li>
	 * </ul>
	 * )
	 * Seite setzt Cookie.
	 * last_url="patient_list/show?'page_definition'=page_definition"
	 *
	 * @param page_definition
	 */
	 public function show(Request $request) {
	//	public function show(Page_definition $page_definition = this.old_page_definition) {
		//Zeige Seite patient_list mit
		$days=new Days;
		$Slots = $days->get_days();
		// Slots von Days,
		// Filter von page_definition.filter,
		
		// $info = [];

		// foreach (Patient::all() as $patient) {
			// $info[$patient->user->name]['Code'] = $patient->code;
			// $info[$patient->user->name]['Tagebuchtag'] = $patient->assignment_day;
			// $info[$patient->user->name]['Änderungen möglich'] = $patient->assignment_day_changes_left;

			// if ($patient->therapist !== null) {
				// $info[$patient->user->name]['Therapeut'] = $patient->therapist->user->name;
			// }
		// }
		// Patientenliste von this.
		//patients(page_definition),
		// session_info.
		//page_definition=page_definition
		
		$params['Slots']=$Slots;
		// return view('therapist.patient_list')->with('Slots', $Slots);
		return view('therapist.patient_list')->with($params);


	}

	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
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
