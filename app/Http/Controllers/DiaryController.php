<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;

use App\Code;
use App\Patient;
use App\Users;

use App\Models\UserRole;

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
     * Es wird der zur als Argument übergebenen Wochennummer gehörende Tagebucheintrag
     * für den angemeldeten Patienten ausgegeben. Soweit ein Kommentar vorhanden ist
     * wird er mit ausgegeben.
     * Je nach Art der Aufgabe und Rolle des Benutzers können Elemente angezeigt werden oder nicht
     * bzw. editiert werden oder nicht:
     * P030, P040, P080, P090: Editierbar
     * Sonst nicht editierbar
     * @param name
     * @param week
     */
    public function entry(Request $request, Patient $patient, $week)
    {
        $patient_info = $patient->to_info()['Patient'];

        /* Begin Löschen wenn assignment_for_week funktioniert */
        // Answer for week 1
        $situation = [];
        $situation['description'] = "Chrakteristisch für die Situation war...";
        $situation['expectation'] = "Meine Erwartungen ...";
        $situation['their_reaction'] = "Die Reaktionen der anderen waren ...";
        $situation['my_reaction'] = "Meine Reaktion ... ";

        $situations = [$situation, $situation, $situation];


        $entry_info = [];
        $entry_info['week'] = $week;
        $entry_info['status'] = "Abgeschickt";
        $entry_info['problem'] = "Beschreiben Sie eine oder mehrere Situationen bei der Rückkehr an Ihren Arbeitsplatz.";
        $entry_info['answer'] = $situations;
        $entry_info['survey']['phq4']['interested'] = 1;
        $entry_info['survey']['phq4']['depressed'] = 2;
        $entry_info['survey']['phq4']['nervous'] = 3;
        $entry_info['survey']['phq4']['troubled'] = 0;
        $entry_info['survey']['wai'] = 5;
        $entry_info['comment'] = "Hier steht der Kommentar des Therapeuten";

        $entry_info['comment_reply']['helpful'] = 1;
        $entry_info['comment_reply']['satisfied'] = 2;


        /* End Löschen wenn assignment_for_week funktioniert */

        /* Einkommentieren wenn assignment_for_week funktioniert
        $assignment=Patient::whereName($patient_info['name'])->assignment_for_week($week);
        $entry_info=$assignment->to_info()['Assignment'];
        */

        $param['PatientInfo'] = $patient_info;
        $param['EntryInfo'] = $entry_info;

        return view('patient/entry')->with($param);
    }

    /**
     * Die Seite mit dem Eintrag zur übergebenen Id wird angezeigt.
     * Es wird überprüft, ob die Id zum Patienten der aktuellen Session gehört. Ist
     * das nicht der Fall so wird aud die Startseite
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
     *    <li>Erste Aufgabe erhalten: Aufgabe editierbar</li>
     *    <li>Erste Aufgabe bearbeitet: Aufgabe editierbar mit zwischengespeichertem
     * Inhalt</li>
     *    <li>Erste Aufgabe abgeschickt: Aufgabe nicht editierbar und Antwort</li>
     *    <li>Erste Aufgabe kommentiert: Aufgabe und Antwort nicht editierbar mit
     * Kommentar</li>
     *    <li>Erste Aufgabe versäumt: Aufgabe nicht editierbar und Hinweis auf
     * Versäumnis</li>
     *    <li>Aktuelle Folgeaufgabe erhalten: Aufgabe editierbar</li>
     *    <li>Aktuelle Folgeaufgabe bearbeitet: Aufgabe editierbar mit
     * zwischengespeichertem Inhalt</li>
     *    <li>Aktuelle Folgeaufgabe abgeschickt: Aufgabe und Antwort nicht
     * editierbar</li>
     *    <li>Aktuelle Folgeaufgabe kommentiert: Aufgabe und Antwort nicht editierbar
     * mit Kommentar</li>
     *    <li>Aktuelle Folgeaufgabe versäumt: Aufgabe nicht editierbar und Hinweis auf
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
     * Speichern der Änderungen zur Frage
     * Der Status des Patienten wird ggf. auf P040, P050 , P060 oder Po65 abgeändert.
     * Der Status des Eintrags wird ggf. auf E015, E030, E040, E050, E060 abgeändert.
     *
     * @param patient
     * @param week
     * @param problem
     * @param content
     * @param survey
     * @param comment
     * @param comment_reply
     */
    public function save_entry(Request $request, Patient $patient, $week)
    {
        /* Wenn die Rolle des Angemeldeten Benutzers patient ist, so sollte geprüft werden, ob $patient identisch
        *  mit dem angemeldeten Benutzer ist.
        */
        /* $entry sollte die Schreibaufgabe des $patient in $week sein */
        /* Hier als Array da die Struktur noch nicht fesgelegt ist */
        $entry = [];
        If ($request->input('problem')) {
            $entry['problem'] = $request->input('problem');
        }
        If ($request->input('content')) {
            $entry['content'] = $request->input('content');
        }
        If ($request->input('survey')) {
            $entry['survey'] = $request->input('survey');
        }
        If ($request->input('comment')) {
            $entry['comment'] = $request->input('comment');
        }
        If ($request->input('comment_reply')) {
            $entry['comment_reply'] = $request->input('comment_reply');
        }
        if ($request->input('entryButton') == "saveDirty") {
            /* Zwischenspeichern von $entry */
            Alert::success("Der Eintrag wurde zwischengespeichert")->flash();
            return Redirect::back();
        } else {
            /* Speichern von $entry */
            Alert::success("Der Eintrag wurde abgeschickt")->flash();
            //return "Abgeschickt";
            return Redirect::to(' / Home');
        }

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
    public function show(Request $request, $name = NULL)
    {
        // Setting default parameter
        if (!$name) {
            $name = Auth::user()->name;
        }

        // $patient=Patient::whereName($name)->first();
        // return dd($patient->assignment_for_week(1));
        /**
         * If the user is a patient, he can only see his own diary
         *
         * TODO: remove null check
         */
        if ($name && $request->user() !== null
            && $request->user()->type === UserRole::PATIENT
            && Auth::user()->name !== $name
        ) {
            return Redirect::to(' / ');
        }

        $Diary = [];
        $Diary['name'] = $name;
        $Diary['patient_week'] = 1;
        $entries = [];
        for ($i = 1; $i <= 12; $i++) {
            $entries[$i]['entry_status'] = 'E010';
            $entries[$i]['problem'] = 'Schreibaufgabe';
        }
        $Diary['entries'] = $entries;
        return view('patient.diary')->with('Diary', $Diary);
    }

}

?>