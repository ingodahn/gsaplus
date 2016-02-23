<?php
namespace App\Models;

/**
 * @author dahn
 * @version 1.1
 * @created 21-Feb-2016 10:29:31
 */
class PatientInfo
{

	function __construct($name)
	{
	}

	function __destruct()
	{
	}



	public function assignment_day()
	{
	}

	/**
	 * Wie oft der Schreibtag geändert werden kann
	 */
	public function assignmentDayChangesLeft()
	{
	}

	/**
	 * Code des Patienten
	 */
	public function code()
	{
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
	}

	/**
	 * Benutzername
	 */
	public function name()
	{
		return $name;
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
	}

	/**
	 * Benutzername des Therapeuten
	 */
	public function therapist()
	{
	}

}
?>