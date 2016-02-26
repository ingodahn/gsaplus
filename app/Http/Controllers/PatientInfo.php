<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Patient;

/**
 * @author dahn
 * @version 1.1
 * @created 21-Feb-2016 10:29:31
 */
class PatientInfo
{

	private $patient;
	private $ordered_assignments;

    public static $STATUS_UNREGISTERED = "P010";
    public static $STATUS_REGISTERED = "P020";
    public static $STATUS_DATE_OF_DEPARTURE_SET = "P025";

    public static $STATUS_PATIENT_GOT_FIRST_ASSIGNMENT = "P030";
    public static $STATUS_PATIENT_EDITED_FIRST_ASSIGNMENT = "P040";
    public static $STATUS_SYSTEM_REMINDED_OF_FIRST_ASSIGNMENT = "P045";
    public static $STATUS_PATIENT_FINISHED_FIRST_ASSIGNMENT = "P050";
    public static $STATUS_THERAPIST_COMMENTED_FIRST_ASSIGNMENT = "P060";
    public static $STATUS_PATIENT_RATED_FIRST_COMMENT = "P065";
    public static $STATUS_PATIENT_MISSED_FIRST_ASSIGNMENT = "P070";

    public static $STATUS_THERAPIST_SET_ACTUAL_ASSIGNMENT = "P075";
    public static $STATUS_PATIENT_GOT_ACTUAL_ASSIGNMENT = "P080";
    public static $STATUS_PATIENT_EDITED_ACTUAL_ASSIGNMENT = "P090";
    public static $STATUS_SYSTEM_REMINDED_OF_ACTUAL_ASSIGNMENT = "P095";
    public static $STATUS_PATIENT_FINISHED_ACTUAL_ASSIGNMENT = "P100";
    public static $STATUS_THERAPIST_COMMENTED_ACTUAL_ASSIGNMENT = "P110";
    public static $STATUS_PATIENT_RATED_ACTUAL_COMMENT = "P115";
    public static $STATUS_PATIENT_MISSED_ACTUAL_ASSIGNMENT = "P120";

    public static $STATUS_COLLABORATION_ENDED = "P130";
    public static $STATUS_INTERVENTION_ENDED = "P140";

    private static $INTERVENTION_PERIOD_IN_WEEKS = "12";

    // system reminds patient after ... days to do the assignment
    private static $REMINDER_PERIOD_IN_DAYS = "5";

    /*
     * TODO:
     * - was passiert wenn eine Aufgabe gemahnt und daraufhin abgeschickt wurde?
     */
    private $status_info = array(
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

	function __construct(Patient $patient)
	{
		$this->patient = $patient;

		$this->refresh();
	}

	public function refresh() {
		$this->ordered_assignments = $this->patient->assignments->sortBy('assigned_on');
	}

    public function status_info() {
        return $this->status_info;
    }

    private function first_assignment_day () {
        return $this->patient->date_from_clinics->copy()->startOfDay()
                    ->endOfWeek()->next($this->patient->assignment_day);
    }

    private function get_last_assignment_day() {
        return Carbon::now()->startOfDay()->previous($this->patient->assignment_day);
    }

	public function assignment_day()
	{
		return $this->patient->assignment_day;
	}

	/**
	 * Wie oft der Schreibtag geändert werden kann
	 */
	public function assignment_day_changes_left()
	{
		return $this->patient->assignment_day_changes_left;
	}

	/**
	 * Code des Patienten
	 */
	public function code()
	{
		return $this->patient->code;
	}

	/**
	 * Entlassungstag
	 */
	public function date_from_clinics()
	{
		return $this->patient->date_from_clinics;
	}

	/**
	 * Datum des letzten Zugriffs auf /Home
	 */
	public function last_activity()
	{
		return $this->patient->last_activity;
	}

	/**
	 * Benutzername
	 */
	public function name()
	{
		return $this->patient->name;
	}

	/**
	 * Notizen der Therapeuten
	 */
	public function notes_of_therapist()
	{
		return $this->patient->notes_of_therapist;
	}

	/**
	 * Prozentsatz der versäumten Tagebucheinträge
	 */
	public function overdue()
	{
		$overdue = $this->patient->assignments()->whereDoesntHave('response')->count();

		return $overdue / $this->ordered_assignments->count();
	}

	/**
	 * Nummer der Woche der Intervention (0...13) oder -1 (falls der Patient
     * noch in der Klinik ist)
	 */
	public function patient_week()
	{
        // -> Ausgangsdatum: Entlassungsdatum
        // -> nächster Schreibtag: Anfang der ersten Woche
        // -> 0-te Woche ist die bis zum ersten Schreibtag
        // -> dann die Differenz vom ersten Schreibtag bis heute berechnen
        //    (in Wochen - nat. hächstens 12 Wochen zählen, ansonsten
		//    ist die Intervention bereits vorbei)
        //

        // Anmerkung: es muss noch kein assignment existieren, also auch nicht mit arbeiten
        // die Wochen zählen, nicht die Aufgaben
        // -> System funktioniert, falls etwas schief läuft

        // shouldn't happen... (day is set during registration)
        if ($this->patient->assignment_day === null) {
            return -1;
        }

        if ($this->patient->date_from_clinics === null
                || $this->patient->date_from_clinics->isFuture()) {
            // patient hasn't left the clinic
            return -1;
        } else if (Carbon::now()->between($this->patient->date_from_clinics, $this->first_assignment_day())) {
            // 0-te Woche falls der erste Schreibtag in der Zukunft liegt
            // und der Patient bereits entlassen wurde
            return 0;
        } else {
            // n-te Woche bei n-1 Wochen Differenz (für n>0)
            // Bsp. 1 Tag nach dem ersten Schreibtag -> 0 Wochen Differenz -> 1-te Woche
            return Carbon::now()->startOfDay()->diffInWeeks($this->first_assignment_day()) + 1;
        }
	}

	/**
	 * persönliche Informationen, die der Patient zur Verfügung stellen kann
	 */
	public function personal_information()
	{
		return $this->patient->personal_information;
	}

	/**
	 * Status des Patienten
	 */
	public function status()
	{
        // Status berechnen

        // first assignment is assigned on the week after departure
        // -> check in which week the patient is
        //      (beginning and end of week is the assignment day)
        // -> then check if an assignment exists for that week
        // ...
        // which parameters do matter?

        // Aufgabe wird am Schreibtag bekannt gegeben (für alle Aufgaben)
        // der Patient sollte die Aufgabe am selben Tag beantworten
        // wenn er das nicht tut, bekommt er eine Erinnerung vom System (5 Tage danach)
        // der Patient kann dann bis zum nächsten Schreibtag antworten
        // am nächsten Schreibtag gilt die Aufgabe als versäumt

        // Carbon - helpful methods:
        // - between($1, $2) - determines if the instance is between two others
        // - isSameDay($1)
        // - max($1)
        // - next($day) - Datum des nächsten $day (Wochentag)

        // WICHTIG: erstmal bis P030

        // TODO: code block too huge... clean up
        // reduce db access?

        $patient_week = $this->patient_week();

        switch ($patient_week) {
            case -1:
                // patient resides in clinic
                if ($this->patient->date_from_clinics !== null) {
                    // date of departure is set and lies in the future
                    return PatientInfo::$STATUS_DATE_OF_DEPARTURE_SET;
                } else {
                    // date of departure isn't set but patient is registered
                    return PatientInfo::$STATUS_REGISTERED;
                }
            case 0:
                // patient has left the clinic but hasn't received
                // an assignment yet
                return PatientInfo::$STATUS_REGISTERED;
            default:
                $is_first_week = ($patient_week === 1);

                // get the actual assignment (assignment count start with 0!)
                $actual_assignment = $this->ordered_assignments->get($this->patient_week() - 1);

                if ($actual_assignment === null) {
                    // shouldn't happen... but
                    // TODO: what should we return if something went wrong?
                    return $is_first_week ? PatientInfo::$STATUS_DATE_OF_DEPARTURE_SET :
                                null;
                } else {
                    if ($actual_assignment->response === null) {
                        if ($actual_assignment->state === true) {
                            // patient has finished assignment
                            return $is_first_week ?
                                PatientInfo::$STATUS_PATIENT_FINISHED_FIRST_ASSIGNMENT :
                                PatientInfo::$STATUS_PATIENT_FINISHED_ACTUAL_ASSIGNMENT;
                        } else if (Carbon::now()->gt($actual_assignment->assigned_on->copy()->addDays(PatientInfo::$REMINDER_PERIOD_IN_DAYS))) {
                            // patient was reminded by system and didn't submit any text
                            // TODO: check if this is really the case! -> check reminders
                            return $is_first_week ?
                                PatientInfo::$STATUS_SYSTEM_REMINDED_OF_FIRST_ASSIGNMENT :
                                PatientInfo::$STATUS_SYSTEM_REMINDED_OF_ACTUAL_ASSIGNMENT;
                        } else if ($actual_assignment->patient_text !== null
                            && strcmp($actual_assignment->patient_text, "") !== 0) {
                            // patient has provided some text
                            return $is_first_week ?
                                PatientInfo::$STATUS_PATIENT_EDITED_FIRST_ASSIGNMENT :
                                PatientInfo::$STATUS_PATIENT_EDITED_ACTUAL_ASSIGNMENT;
                        } else {
                            return $is_first_week ?
                                PatientInfo::$STATUS_PATIENT_GOT_FIRST_ASSIGNMENT :
                                PatientInfo::$STATUS_PATIENT_GOT_ACTUAL_ASSIGNMENT;
                        }
                    } else {
                        if ($actual_assignment->response->rating !== null) {
                            return $is_first_week ?
                                PatientInfo::$STATUS_PATIENT_RATED_FIRST_COMMENT :
                                PatientInfo::$STATUS_PATIENT_RATED_ACTUAL_COMMENT;
                        } else {
                            return $is_first_week ?
                                PatientInfo::$STATUS_THERAPIST_COMMENTED_FIRST_ASSIGNMENT :
                                PatientInfo::$STATUS_THERAPIST_COMMENTED_ACTUAL_ASSIGNMENT;
                        }
                    }
                }
        }
	}

	/**
	 * Benutzername des Therapeuten
	 */
	public function therapist()
	{
		return $this->patient->therapist;
	}

}
?>