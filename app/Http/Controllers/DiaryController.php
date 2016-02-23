<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers;

use App\Code;
use App\Patient;
use App\Users;

/**
 * @author dahn
 * @version 1.0
 * @created 12-Feb-2016 19:45:53
 */
class DiaryController extends Controller
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Füge eine neue Situation zur ersten Schreibaufgabe hinzu
	 */
	public function add_situation()
	{
	}

	/**
	 * Der übergebene Content wird für den Patienten unter der verwendeten Id
	 * gespeichert. Der Status des Patienten wird geändert von
	 * <ul>
	 * 	<li>P50 auf P60 bzw. von</li>
	 * 	<li>P80 auf P90</li>
	 * </ul>
	 * Ansonsten bleibt der Status unverändert
	 * Es wird keine Seite an den Client zurückgegeben.
	 *
	 * @param Content
	 * @param Id
	 */
	public function auto_save($Content, $Id)
	{
	}

	/**
	 * Zeige alle bisherigen Beiträge sowie die dazu erfolgten Kommentare auf einer
	 * Seite an, die vom Browser gespeichert oder ausgedruckt werden kann.
	 */
	public function commented_diary()
	{
	}

	/**
	 * Zeige den aktuellen Eintrag
	 */
	public function current()
	{
	}

	/**
	 * Zeige den vorhergehenden Eintrag
	 *
	 * @param week
	 * @param patient
	 */
	public function diary_back($week, $patient)
	{
	}

	/**
	 * Zeige den folgenden Eintrag
	 *
	 * @param week
	 * @param patient
	 */
	public function diary_forward($week, $patient)
	{
	}

	/**
	 * Es wird der zur als Argument übergebenen Wochennummer gehörende Tagebucheintrag
	 * für den angemeldeten Patienten ausgegeben. Soweit ein Kommentar vorhanden ist
	 * wird er mit ausgegeben.
	 * Je nach Art der Aufgabe und Status des Patienten kann der Text editiert werden
	 * oder nicht:
	 * P030, P040, P080, P090: Editierbar
	 * Sonst nicht editierbar
	 *
	 * @param WeekNr
	 */
	public function entry($WeekNr)
	{
	}

	/**
	 * Die Seite mit dem Eintrag zur übergebenen Id wird angezeigt.
	 * Es wird überprüft, ob die Id zum Patienten der aktuellen Session gehört. Ist
	 * das nicht der Fall so wird der Cookie gelöscht und es wird aud die Startseite
	 * weitergeleitet.
	 * Je nach Status des Patienten wird die anzuzeigende Seite ansonsten gestaltet:
	 * Ist nicht die aktuelle Aufgabe ausgewählt, so weirden die gewählte Aufgabe und
	 * der Kommentar nicht editierbar angezeigt.
	 * Ist die aktuelle Aufgabe ausgewählt, so wird die aktuelle Aufgabe je nach
	 * Status des Patienten angezeigt. dabei werden unterschiedliche Seiten
	 * ausgeliefert, je nachdem ob es sich um die erste Aufgabe oder eine Folgeaufgabe
	 * handelt.
	 * Die folgenden Fälle sind relevant (s. Patient_status):
	 * <ul>
	 * 	<li>Erste Aufgabe erhalten: Aufgabe editierbar</li>
	 * 	<li>Erste Aufgabe bearbeitet: Aufgabe editierbar mit zwischengespeichertem
	 * Inhalt</li>
	 * 	<li>Erste Aufgabe abgeschickt: Aufgabe nicht editierbar und Antwort</li>
	 * 	<li>Erste Aufgabe kommentiert: Aufgabe und Antwort nicht editierbar mit
	 * Kommentar</li>
	 * 	<li>Erste Aufgabe versäumt: Aufgabe nicht editierbar und Hinweis auf
	 * Versäumnis</li>
	 * 	<li>Aktuelle Folgeaufgabe erhalten: Aufgabe editierbar</li>
	 * 	<li>Aktuelle Folgeaufgabe bearbeitet: Aufgabe editierbar mit
	 * zwischengespeichertem Inhalt</li>
	 * 	<li>Aktuelle Folgeaufgabe abgeschickt: Aufgabe und Antwort nicht
	 * editierbar</li>
	 * 	<li>Aktuelle Folgeaufgabe kommentiert: Aufgabe und Antwort nicht editierbar
	 * mit Kommentar</li>
	 * 	<li>Aktuelle Folgeaufgabe versäumt: Aufgabe nicht editierbar und Hinweis auf
	 * Versäumnis</li>
	 * </ul>
	 *
	 * @param entry_id
	 */
	public function get_response($entry_id)
	{

		//if (not actual assignment) {
		// return view(diary.entry_noneditable)->
		//where('Content'="Complete content",
		//Comment="Comment");
		//Result: Not Actual
		//} else if (first assignment) {
		//Result: First
		//} else {
		// Result: Successive
		//}


	}

	/**
	 * Speichere das assignment_text-Argument mit Titel assignment_ title als neue
	 * Schreibaufgabe ab
	 *
	 * @param assignment_text
	 * @param assignment_title
	 */
	public function new_assignment($assignment_text, $assignment_title)
	{
	}

	/**
	 * Speichern der Antwort (Text) zur Frage mit der gegebenen Id.
	 * Der Status des Patienten wird
	 * <ul>
	 * 	<li>von P030 oder P040 auf P050 bzw.</li>
	 * 	<li>von P080 oder P090 auf P100 abgeändert.</li>
	 * </ul>
	 *
	 * @param Text
	 * @param Id
	 */
	public function save_response($Text, $Id)
	{
	}

	/**
	 * Wähle die Afgabe mit dem angegebenen Titel aus und trage den Aufgaben-text in
	 * entry.problem ein
	 *
	 * @param assignment_id
	 */
	public function select_assignment($assignment_id)
	{
	}

	/**
	 * Zeigt das Tagebuch des Patienten mit dem Benutzernamen name
	 *
	 * @param name
	 */
	public function show(Request $request,$name=NULL)
	{
		// Setting default parameter
		if (! $name) {
			$name = Auth::user()->name;
		}
		/**
		 * If the user is a patient, he can only see his own diary
		 *
		 * TODO: remove null check
		 */
		if ($name && $request->user() !== null
			&& $request->user()->type === 'patient' && Auth::user()->name !== $name) {
			return Redirect::to('/');
		}
		// return $name;
		return view('patient.diary')->with('name',$name);
	}

}
?>