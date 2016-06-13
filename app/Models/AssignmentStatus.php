<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 29.02.2016
 * Time: 19:40
 */

namespace App\Models;


class AssignmentStatus
{

    const ASSIGNMENT_IS_NOT_DEFINED = "E010";

    const THERAPIST_SAVED_ASSIGNMENT = "E015";
    const PATIENT_GOT_ASSIGNMENT = "E020";
    const PATIENT_EDITED_ASSIGNMENT = "E030";
    const ASSIGNMENT_WILL_BECOME_DUE_SOON = "E035";
    const PATIENT_FINISHED_ASSIGNMENT = "E040";
    const THERAPIST_COMMENTED_ASSIGNMENT = "E050";
    const PATIENT_RATED_COMMENT = "E060";
    const PATIENT_MISSED_ASSIGNMENT = "E070";

    const ASSIGNMENT_IS_NOT_REQUIRED = "E100";

    const UNKNOWN = "E000";

    public static $STATUS_INFO = array(
        self::ASSIGNMENT_IS_NOT_DEFINED => "Schreibimpuls nicht definiert",
        self::THERAPIST_SAVED_ASSIGNMENT => "Schreibimpuls definiert",
        self::PATIENT_GOT_ASSIGNMENT => "Schreibimpuls liegt für Sie bereit",
        self::PATIENT_EDITED_ASSIGNMENT => "Tagebucheintrag in Bearbeitung und zwischengespeichert",
        self::ASSIGNMENT_WILL_BECOME_DUE_SOON => "Tagebucheintrag in Kürze fällig",
        self::PATIENT_FINISHED_ASSIGNMENT => "Tagebucheintrag abgeschickt",
        self::THERAPIST_COMMENTED_ASSIGNMENT => "Ihr Online-Therapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung.",
        self::PATIENT_RATED_COMMENT => "Tagebucheintrag und Rückmeldung abgeschlossen",
        self::PATIENT_MISSED_ASSIGNMENT => "Kein Tagebucheintrag vorhanden",
        self::ASSIGNMENT_IS_NOT_REQUIRED => "Kein aktueller Schreibimpuls",
        self::UNKNOWN => "Unbekannt"
    );

    public static function to_patient_status($assignment_status) {
        switch ($assignment_status) {
            case AssignmentStatus::PATIENT_GOT_ASSIGNMENT:
                return PatientStatus::PATIENT_GOT_ASSIGNMENT;
            case AssignmentStatus::PATIENT_EDITED_ASSIGNMENT:
                return PatientStatus::PATIENT_EDITED_ASSIGNMENT;
            case AssignmentStatus::ASSIGNMENT_WILL_BECOME_DUE_SOON:
                return PatientStatus::ASSIGNMENT_WILL_BECOME_DUE_SOON;
            case AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT:
                return PatientStatus::PATIENT_FINISHED_ASSIGNMENT;
            case AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT:
                return PatientStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            case AssignmentStatus::PATIENT_RATED_COMMENT:
                return PatientStatus::PATIENT_RATED_COMMENT;
            case AssignmentStatus::PATIENT_MISSED_ASSIGNMENT:
                return PatientStatus::PATIENT_MISSED_ASSIGNMENT;
        }

        return PatientStatus::UNKNOWN;
    }

}
