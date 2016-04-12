<?php

namespace App\Http\Controllers;

use App\Models\AssignmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use UxWeb\SweetAlert\SweetAlert as Alert;

use App\Code;
use App\Patient;
use App\Users;
use App\Situation;
use App\Survey;
use App\PHQ4;
use App\WAI;
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
        /*  Eigentlich brauche ich nur die Information für den Patienten
        * und die Information für einen Eintrag rekursiv aufgelöst.
         * $assignment=$patient->assignment_for_week($week);
         * $assignment_info = $assignment->all_info();
         * funktioniert aber nicht
         * Deshalb muss ich alle assignments holen
         */
        $patient->save();
        // $info = $patient->all_info();
        $patient_info = $patient->info_with();

        // $assignment_info=$info['assignments'][$week-1];
        $assignment_info=$patient->assignment_for_week($week)->all_info();

        $entry_info = [];
        $entry_info['week'] = $assignment_info['week'];
        $entry_info['status'] = $assignment_info['assignmentStatus'];
        $entry_info['status_text'] = AssignmentStatus::$STATUS_INFO[$assignment_info['assignmentStatus']];
       // if ($week == 1) { // !!! uncomment for M4 !!!
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
        // } else {    // week 2 and later !!!uncomment for M4

        // }    // !!! uncomment for M4
        
        if (! array_key_exists('survey',$assignment_info)) {
            $assignment_info['survey']=[];
        }
        if (! array_key_exists('phq4',$assignment_info['survey'])){
            $assignment_info['survey']['phq4']=[];
        }
        if (! array_key_exists('depressed',$assignment_info['survey']['phq4'])){
            $assignment_info['survey']['phq4']['depressed']=-1;
        }
        if (! array_key_exists('nervous',$assignment_info['survey']['phq4'])){
            $assignment_info['survey']['phq4']['nervous']=-1;
        }
        if (! array_key_exists('interested',$assignment_info['survey']['phq4'])){
            $assignment_info['survey']['phq4']['interested']=-1;
        }
        if (! array_key_exists('troubled',$assignment_info['survey']['phq4'])){
            $assignment_info['survey']['phq4']['troubled']=-1;
        }
        $entry_info['survey'] = $assignment_info['survey'];
        if (! array_key_exists('wai',$assignment_info['survey'])){
            $assignment_info['survey']['wai']=['index'=> -1];
        }

        $entry_info['survey']['wai']=$assignment_info['survey']['wai']['index'];
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
        /* ToDo:  comment_reply mit Werten aus Datenbank aktualisieren */
        $entry_info['comment_reply']['helpful'] = $assignment_info['commentReply']['helpful'];
        $entry_info['comment_reply']['satisfied'] = $assignment_info['commentReply']['satisfied'];
        // return dd($entry_info);

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
        $assignment=$patient->assignment_for_week($week);
        if ($week == 1) {
            if ($request->has('situation0_description')) { // situations were editable
                $situations=$assignment->situations->all();
                if ($situations == []){
                    for ($i=0; $i<=2; $i++){
                        $situation=new Situation;
                        $assignment->situations()->save($situation);
                    }
                } // Now we are sure that the situations exist and we get it again.
                $situations=$assignment->situations;
                for ($i=0; $i<=2; $i++){
                    $situation=$situations->get($i);
                    $situation->description = $request->input('situation'.$i.'_description');
                    $situation->expectation = $request->input('situation'.$i.'_expectations'); //note: Plural in request
                    $situation->my_reaction = $request->input('situation'.$i.'my_reaction');
                    $situation->their_reaction = $request->input('situation'.$i.'their_reaction');
                    // and save it
                    $situation->save();
                }
            }
        } else {
            // To do for M4: handling of problem and reflection
            /*If ($request->has('problem')) {
                $assignment->problem = $request->input('problem');
            }
            If ($request->has('reflection')) {
                $assignment->'answer' = $request->input('reflection');
            }*/
        }

        $assignment_info=$assignment->info();

        if ($request->has('survey_wai') ||
        $request->has('phq4_interested') ||
        $request->has('phq4_depressed') ||
        $request->has('phq4_nervous') ||
        $request->has('phq4_troubled')) { // Survey is edited
          //  if (! array_key_exists('survey',$assignment_info)) { // no survey yet
            if ($assignment->survey->all() == []) { // no survey yet - we create a complete survey with default values
                $survey = new Survey();
                $assignment->survey()->save($survey);
                $wai = new WAI;
                $survey->wai()->save($wai);
                $phq4 = new PHQ4;
                $survey->phq4()->save($phq4);
            } else {
                $survey = $assignment->survey->first();
                $wai = $survey->wai->first();
                $phq4 = $survey->phq4->first();
            }
            if ($request->has('phq4_interested')) {
                $phq4->interested = $request->input('phq4_interested');
            }
            if ($request->has('phq4_depressed')) {
                $phq4->depressed = $request->input('phq4_depressed');
            }
            if ($request->has('phq4_nervous')) {
                $phq4->nervous = $request->input('phq4_nervous');
            }
            if ($request->has('phq4_troubled')) {
                $phq4->troubled = $request->input('phq4_nervous');
            }
            $phq4->save();
            if ($request->has('survey_wai')){
                $wai->index = $request->input('survey_wai');
            }
            $wai->save();
        }
        if ($request->has('comment')) {
            if ($assignment->comment->all() == []){
                $comment = new Comment;
                $assignment->comment()->save($comment);
            }
            $comment = $assignment->comment->first();
            $comment->text = $request->input('comment');
            $comment->save();
        }
        if ($request->has('comment_reply_satisfied') ||
        $request->has('comment_reply_helpful')) {
            $comment = $assignment->comment->first();
            if ($comment->comment_reply->all()==[]){
                $comment_reply=new CommentReply;
                $comment->comment_reply()->save($comment_reply);
            }
            $comment_reply = $comment->comment_reply->first();
            if ($request->has('comment_reply_helpful')) {
                $comment_reply->helpful = $request->input('comment_reply_helpful');
            }
            if ($request->has('comment_reply_satisfied')) {
                $comment_reply->helpful = $request->input('comment_reply_satisfied');
            }
        }
        if ($request->input('entryButton') == "saveDirty") {
            /* Zwischenspeichern von $entry */
            $assignment->dirty = true;
            $assignment->save();
            return dd($assignment->all_info());
            Alert::success("Der Eintrag wurde zwischengespeichert")->persistent();
            return Redirect::back();
        } else {
            /* Speichern von $entry */
            $assignment->dirty = false;
            $assignment->save();
            Alert::success("Der Eintrag wurde abgeschickt")->persistent();
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
        $info=$patient->all_info();
        // $info=$patient->to_info([], null, ['assignments']);
        $Diary['name'] = $info['name'];
        $Diary['patient_week'] = $info['patientWeek'];
        if ($Diary['patient_week'] < 0) {
            $Diary['patient_week'] = 0;
        }
        /*if (array_key_exists('assignments',$info)) {
           $assignment_info = $info['assignments'];
        } else {
            $info['assignments']=[];
        }*/
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

}

?>
