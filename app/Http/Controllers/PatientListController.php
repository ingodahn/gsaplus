<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
 

use App\Models;
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
 * page_definition enthält die Beschreibung der aktuellen Sortier- und
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
	 * Liefert Seite mit allen Patienten mit der bisher gewählten Sortierung
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
	 * sort_field wird ersetzt. Wenn sort_field dabei nicht geändert wird, wird
	 * sort_order geändert
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
		// Patientenliste von this.
		//patients(page_definition),
		// session_info.
		//page_definition=page_definition
		return view('therapist.patient_list');


	}

}
?>