<?php

namespace App\Http\Controllers;

use App\Models\AssignmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use UxWeb\SweetAlert\SweetAlert as Alert;

use App\Code;
use App\Patient;

use App\Situation;
use App\Survey;

use App\Comment;
use App\CommentReply;

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
     * Füge eine neue Situation zum ersten Schreibimpuls hinzu
     */
    public function add_situation()
    {
    }


    /**
     * Zeige alle bisherigen Beiträge sowie die dazu erfolgten Kommentare auf einer
     * Seite an, die vom Browser gespeichert oder ausgedruckt werden kann.
     */
    public function commented_diary(Request $request, $name)
    {
        $isTherapist = (Auth::user()->type === UserRole::THERAPIST);
        $patient=Patient::whereName($name)->first();
        $info=$patient->all_info();
        $p_assignments=$info['assignments'];
        // return dd($p_assignments);
        $wai=[];
        $health=[];
        $assignments=[];
        $params=[];

        for ($i=1; $i <= $info['patientWeek']; $i++) {
            if (isset($p_assignments[$i - 1]['survey'])) {
                $wai[$i] = $p_assignments[$i - 1]['survey']['wai'];
                $health[$i] = $p_assignments[$i - 1]['survey']['health'];
            } else {
                $wai[$i] = -1;
                $health[$i] = -1;
            }
        }
// return dd($p_assignments);
        $assignments[1]['problem']='Beschreiben Sie eine oder mehrere Situationen bei der Rückkehr an Ihren Arbeitsplatz.';
        if (isset($p_assignments[0]['situations'])) {
            $assignments[1]['answer']=$p_assignments[0]['situations'];
        } else {
            $assignments[1]['answer']="";
        }
        
        $assignments[1]['dirty']=$p_assignments[0]['dirty'];

        for ($i=2; $i <= $info['patientWeek']; $i++) {
            if (isset($p_assignments[$i-1]['problem'])){
                $assignments[$i]['problem']=$p_assignments[$i-1]['problem'];
            } else {
                $assignments[$i]['problem']="Nicht definiert";
            }
            if (isset($p_assignments[$i-1]['answer'])) {
                    $assignments[$i]['answer']=$p_assignments[$i-1]['answer'];
                    $assignments[$i]['dirty']=$p_assignments[$i-1]['dirty'];
            } else {
                $assignments[$i]['answer']="Nicht beantwortet";
                $assignments[$i]['dirty']=false;
            }
            if (isset($p_assignments[$i-1]['comment']['text'])) {
                $assignments[$i]['comment'] = $p_assignments[$i-1]['comment']['text'];
            } else {
                $assignments[$i]['comment'] = "Nicht kommentiert";
            }
        }
        $params['PatientName']=$name;
        $params['Week']=$info['patientWeek'];
        $params['Wai']=$wai;
        $params['Health']=$health;
        $params['Assignments']=$assignments;
        // return dd($params);
        return view('patient/commented_diary')->with($params);
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
        $patient_info = $patient->info();
// All assignments exist after registration, so we can grab it
        $assignment_info=$patient->assignment_for_week($week)->all_info();
        $entry_info = [];
        $entry_info['week'] = $assignment_info['week'];
        $entry_info['status'] = $assignment_info['assignmentStatus'];
        $entry_info['status_text'] = AssignmentStatus::$STATUS_INFO[$assignment_info['assignmentStatus']];
       if ($week == 1) {
            $entry_info['problem'] = "Beschreiben Sie eine oder mehrere Situationen bei der Rückkehr an Ihren Arbeitsplatz.";
            if (!array_key_exists('situations', $assignment_info)) {
                $assignment_info['situations'] = [];
            }
            $entry_info['answer'] = $assignment_info['situations']; // Now $entry_info['answer'] exists and has values from $assignment_info['situations' IF these exist
            $empty_situation = [
                "description" => "",
                "expectation" => "",
                "myReaction" => "",
                'theirReaction' => ""
            ];

            for ($i = 0; $i <= 2; $i++) {
                if (!array_key_exists($i, $entry_info['answer'])) { // filling missing situation with $empty_situation
                    $entry_info['answer'][$i] = $empty_situation;
                }
                // This is easier than fetching each situation, setting the to_camel_case attribute and re-generating the info:
                $entry_info['answer'][$i]['my_reaction'] = $entry_info['answer'][$i]['myReaction'];
                $entry_info['answer'][$i]['their_reaction'] = $entry_info['answer'][$i]['theirReaction'];
            }
        } else {    // week 2 and later
           $entry_info['problem']=$assignment_info['problem'];
           $entry_info['reflection'] = $assignment_info['answer'];
        }

        if (! array_key_exists('survey', $assignment_info)) {
            $assignment_info['survey']=[
                "health" => -1,
                "wai" => -1
            ];
        }

        $entry_info['survey'] = $assignment_info['survey'];

        if (! array_key_exists('comment',$assignment_info)) {
            $assignment_info['comment']=['text' => ""];
        }
        $entry_info['comment'] = $assignment_info['comment']['text'];

        if (! array_key_exists('commentReply',$assignment_info)) {
            $assignment_info['commentReply']=[];
        }
        if (! array_key_exists('helpful',$assignment_info['commentReply'])) {
            $assignment_info['commentReply']['helpful']=-1;
        }
        if (! array_key_exists('satisfied',$assignment_info['commentReply'])) {
            $assignment_info['commentReply']['satisfied']=-1;
        }

        $entry_info['comment_reply']['helpful'] = $assignment_info['commentReply']['helpful'];
        $entry_info['comment_reply']['satisfied'] = $assignment_info['commentReply']['satisfied'];

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
     * Ist nicht der aktuelle Schreibimpuls ausgewählt, so werden der gewählte
     * Schreibimpuls und der Kommentar nicht editierbar angezeigt.
     * Ist die aktuelle Aufgabe ausgewählt, so wird die aktuelle Aufgabe je nach
     * Status des Patienten angezeigt. dabei werden unterschiedliche Seiten
     * ausgeliefert, je nachdem ob es sich um den ersten Schreibimpuls oder einen
     * Folgeschreibimpuls handelt.
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
        $is_therapist = ($request->user()->type === UserRole::THERAPIST);
        $is_patient = ($request->user()->type === UserRole::PATIENT);
        $assignment = $patient->assignment_for_week($week);

        if ($week == 1) {
            $assignment->load('situations');

            if ($request->exists('situation0_description')) { // situations were editable
                $situation_count = 0;
                $situations = [];

                while($request->exists('situation'.$situation_count.'_description')) {
                    $saved_situation = $assignment->situations->get($situation_count);

                    // check whether situation exists, create new situation if needed
                    $situation = $saved_situation ? $saved_situation : Situation::create();

                    // fill in the details (supplied by the user)
                    $situation->description = $request->input('situation'.$situation_count.'_description');
                    $situation->expectation = $request->input('situation'.$situation_count.'_expectations'); //note: Plural in request
                    $situation->my_reaction = $request->input('situation'.$situation_count.'_my_reaction');
                    $situation->their_reaction = $request->input('situation'.$situation_count.'_their_reaction');

                    $situations[] = $situation;

                    // process next set
                    $situation_count++;
                }

                // save or update the situations
                $assignment->situations()->saveMany($situations);
            }
        } else {
            // Get problem and answer from database
            If ($request->has('problem')) {
                $assignment->problem = $request->input('problem');
            }
            If ($request->has('reflection')) {
                $assignment->answer = $request->input('reflection');
            }
        }

         if ($request->has('wai') || $request->has('health')) { // Survey is edited
             if ($assignment->survey === null) { // no survey yet
                 // no survey yet - we create a complete survey with default values
                 $survey = Survey::create();

                 $assignment->survey()->save($survey);
             } else {
                 $survey = $assignment->survey;
             }

             if ($request->has('health')) {
                 $survey->health = $request->input('health');
             }
             if ($request->has('wai')) {
                 $survey->wai = $request->input('wai');
             }

             $survey->save();
         }
        // Use this as template for generating and instantiating objects
         if ($request->has('comment')) {
            $comment = $assignment->comment ?: new Comment;
            $comment->text = $request->input('comment');

            if ($assignment->comment) {
                $comment->save();
            } else {
                $assignment->comment()->save($comment);
            }
        }
        if ($request->has('comment_reply_satisfied') ||
        $request->has('comment_reply_helpful')) {
            $comment = $assignment->comment;
            $comment_reply = $comment->comment_reply ?: new CommentReply;
            if ($request->has('comment_reply_helpful')) {
                $comment_reply->helpful = $request->input('comment_reply_helpful');
            }
            if ($request->has('comment_reply_satisfied')) {
                $comment_reply->helpful = $request->input('comment_reply_satisfied');
            }
            if ($comment->comment_reply) {
                $comment_reply->save();
            } else {
                $comment->comment_reply()->save($comment_reply);
            }
        }

        if ($request->has('notesOfTherapist')) {
            $patient->notes_of_therapist = $request->input('notesOfTherapist');
            $patient->save();
            // ToDo: Send mail to patient informing that entry has been commented
        }
        if ($request->input('entryButton') == "saveDirty") {
            /* Zwischenspeichern von $entry */
            $assignment->dirty = true;
            $assignment->save();
            // return dd($assignment->all_info());
            Alert::success("Der Eintrag wurde zwischengespeichert")->persistent();
            return Redirect::back();
        } else {
            /* Speichern von $entry */
            if ($is_patient) {
                $assignment->dirty = false;
            }
            $assignment->save();
            Alert::success("Der Eintrag wurde abgeschickt")->persistent();
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
        $is_patient = ($request->user()->type === UserRole::PATIENT);
        if ($name && $request->user() !== null
            && $is_patient
            && Auth::user()->name !== $name
        ) {
            return Redirect::to('/');
        }

        $Diary = [];

        $patient=Patient::whereName($name)->first();

        if ($is_patient) {
            $patient->last_activity=time();
            $patient->save();
        }

        $info=$patient->all_info();
        // $info=$patient->to_info([], null, ['assignments']);
        $Diary['name'] = $info['name'];
        $Diary['patient_week'] = $info['patientWeek'];
        if ($Diary['patient_week'] < 0) {
            $Diary['patient_week'] = 0;
        }

        $patient_week=$Diary['patient_week'];
        $next_assignment_date=$patient->next_assignment() ? $patient->next_assignment()->writing_date : null;
        if ($next_assignment_date) {
            $Diary['next_assignment'] = "Der nächste Schreibimpuls wird am ".$next_assignment_date->format('d.m.Y')." gegeben.";
        } elseif ($info['patientStatus'] == 'P020') {
            $Diary['next_assignment'] = "Der erste Schreibimpuls wird nach Übermittlung des Entlassungsdatums aus der Klinik gegeben. Sie werden darüber per E-Mail informiert.";
        }
        else {
            $Diary['next_assignment'] = "Es ist kein weiterer Schreibimpuls vorgesehen.";
        }

        $assignment_info = $info['assignments'];
        $entries = [];
        $entries[1]['problem']="Beschreiben Sie typische Situationen...";
        $entries[1]['entry_status'] = AssignmentStatus::$STATUS_INFO[$assignment_info[0]['assignmentStatus']];
        $weeks_to_show=$Diary['patient_week'];
        if (Auth::user()->type == UserRole::THERAPIST) {
            $weeks_to_show = 12;
        }

        for ($i = 2; $i <= $weeks_to_show; $i++) {
            $i1=$i-1;
            if ($i <= $info['patientWeek']) {
                $entries[$i]['entry_status'] = AssignmentStatus::$STATUS_INFO[$assignment_info[$i1]['assignmentStatus']];
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

    /* Returning a default for null value */
    private function value_with_default($value,$default) {
        if ($value) {
            return $value;
        } else {
            return $default;
        }
    }
}


?>
