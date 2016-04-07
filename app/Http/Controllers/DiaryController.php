<?php

namespace App\Http\Controllers;

use App\Models\AssignmentStatus;
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
        $patient_info = $patient->to_info();

        $assignment=$patient->assignment_for_week($week);

        $assignment_info=$assignment->to_info([], null, ['situations','survey','comment','commentReply']);
        $survey=$assignment->survey;

        $survey_info = $survey->to_info([], null, ['phq4','wai']);

        $entry_info = [];
        $entry_info['week'] = $assignment_info['week'];
        $entry_info['status'] = $assignment_info['status']; // Jetzt als Code, besser wäre Klartext
        $entry_info['problem'] = "Beschreiben Sie eine oder mehrere Situationen bei der Rückkehr an Ihren Arbeitsplatz.";
        $entry_info['answer'] = $assignment_info['situations'];
        return dd($entry_info);
        for ($i=0;$i<=2;$i++){ // This is easier than fetching each situation, setting the to_camel_case attribute and re-generating the info
            $entry_info['answer'][$i]['my_reaction']=$entry_info['answer'][$i]['myReaction'];
            $entry_info['answer'][$i]['their_reaction']=$entry_info['answer'][$i]['theirReaction'];
        }
        $entry_info['survey'] = $survey_info;
        $entry_info['survey']['wai']=$survey_info['wai']['index'];

        $entry_info['comment'] = $assignment_info['comment']['text'];

        /* ToDo:  comment_reply mit Werten aus Datenbank aktualisieren */
        $entry_info['comment_reply']['helpful'] = 1;
        $entry_info['comment_reply']['satisfied'] = 2;


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
            return Redirect::to('/Home');
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
            return Redirect::to('/');
        }

        $Diary = [];

        $patient=Patient::whereName($name)->first();
        $info=$patient->to_info([], null, ['assignments']);
        $Diary['name'] = $info['name'];
        $Diary['patient_week'] = $info['patientWeek'];
        if (array_key_exists('assignments',$info)) {
           $assignment_info = $info['assignments'];
        } else {
            $info['assignments']=[];
        }
        $entries = [];
        $entries[1]['problem']="Beschreiben Sie typische Situationen...";
        $entries[1]['entry_status'] = AssignmentStatus::$STATUS_INFO[$assignment_info[0]['status']];
        $weeks_to_show=$info['patientWeek'];
        if (Auth::user()->type == UserRole::THERAPIST) {
            $weeks_to_show = 12;
        }

        for ($i = 2; $i <= $weeks_to_show; $i++) {
            $i1=$i-1;
            if ($i <= $info['patientWeek']) {
                $entries[$i]['entry_status'] = AssignmentStatus::$STATUS_INFO[$assignment_info[$i1]['status']];
            } else {
                $entries[$i]['entry_status']='';
            }
            $string = $assignment_info[$i1]['problem'];
            if (strlen($string) > 30)
            {
                $string = wordwrap($string, 30);
                $string = substr($string, 0, strpos($string, "\n"));
            }
            $entries[$i]['problem'] = $string." ...";
        }

            for ($j=$weeks_to_show+1; $j<=12; $j++) {

                $entries[$j]['problem']='';
                $entries[$j]['entry_status']='';
            }

        $Diary['entries'] = $entries;
        return view('patient.diary')->with('Diary', $Diary);
    }

}

?>