<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Patient;
use App\Models\PatientStatus;

/**
 * @author dahn
 * @version 1.1
 * @created 21-Feb-2016 10:29:31
 */
class PatientInfo
{

	private $patient;

    const INTERVENTION_PERIOD_IN_WEEKS = "12";

    // system reminds patient after ... days to do the assignment
    const REMINDER_PERIOD_IN_DAYS = "5";

	function __construct(Patient $patient)
	{
		$this->patient = $patient;
	}


}
?>