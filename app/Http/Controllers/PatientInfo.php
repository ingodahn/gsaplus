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

	function __construct(Patient $patient)
	{
		$this->patient = $patient;
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

	}

	/**
	 * Datum des letzten Zugriffs auf /Home
	 */
	public function lastActivity()
	{
			return 'never';
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
	}

	/**
	 * Prozentsatz der versäumten Tagebucheinträge
	 */
	public function overdue()
	{
	}

	/**
	 * Nummer der Woche der Intervention (0...13)
	 */
	public function patientWeek()
	{
		return 1;
	}

	/**
	 * persönliche Informationen, die der Patient zur Verfügung stellen kann
	 */
	public function personalInformation()
	{
	}

	/**
	 * Status des Patienten
	 */
	public function status()
	{
		// Status berechnen
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