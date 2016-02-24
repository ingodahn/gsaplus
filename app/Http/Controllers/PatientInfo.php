<?php
namespace App\Http\Controllers;

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

    public function get_status_info() {
        return $this->status_info;
    }

	public function assignment_day()
	{
		return $this->patient->assignment_day;
	}

	/**
	 * Wie oft der Schreibtag geändert werden kann
	 */
	public function assignmentDayChangesLeft()
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
	public function dateFromClinics()
	{
		return $this->patient->date_from_clinics;
	}

	/**
	 * Datum des letzten Zugriffs auf /Home
	 */
	public function lastActivity()
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
	public function notes()
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
	 * Nummer der Woche der Intervention (0...13)
	 */
	public function patientWeek()
	{
        // -> Ausgangsdatum: Entlassungsdatum
        // -> nächster Schreibtag: Anfang der ersten Woche
        // -> dann die Differenz bis heute berechnen (nat. < 12 Wochen, ansonsten
		//    ist die Intervention bereits vorbei)
        // -> es muss noch kein assignment existieren, also auch nicht mit arbeiten
        $date_of_departure = $this->patient->date_from_clinics;

        $first_assignment_day = Carbon::parse($date_of_departure->toDateTimeString());
        $first_assignment_day->startOfWeek();
        $first_assignment_day->addDays($this->patient->assignment_day);

        $weeks_passed = Carbon::now()->diffInWeeks($first_assignment_day);

		return $weeks_passed;
	}

	/**
	 * persönliche Informationen, die der Patient zur Verfügung stellen kann
	 */
	public function personalInformation()
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
        // -> check in which week the patient is (beginning and end of week is the assignment day
        // -> then check if an assignment exists for that week
        // ...
        // which parameters do matter?


	}

	/**
	 * Benutzername des Therapeuten
	 */
	public function therapist()
	{
		return $this->patient->therapist->name;
	}

}
?>