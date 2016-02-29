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
    const PATIENT_FINISHED_ASSIGNMENT = "E040";
    const THERAPIST_COMMENTED_ASSIGNMENT = "E050";
    const PATIENT_RATED_COMMENT = "E060";
    const SYSTEM_REMINDED_OF_ASSIGNMENT = "E070";

    const ASSIGNMENT_IS_NOT_REQUIRED = "E100";

    const UNKNOWN = "E000";

    public static $STATUS_INFO = array(
        "E000"=>"E000: Unbekannt",
        "E010"=>"E010: Aufgabe nicht definiert",
        "E015"=>"E015: Aufgabe definiert",
        "E020"=>"E020: Aufgabe gestellt",
        "E030"=>"E030: Aufgabe bearbeitet",
        "E040"=>"E040: Aufgabe abgeschickt",
        "E050"=>"E050: Antwort kommentiert",
        "E060"=>"E060: Kommentar bewertet",
        "E070"=>"E070: Aufgabe überfällig",
        "E100"=>"E100: Aufgabe nicht erforderlich"
    );

    public static function to_patient_status($entry_status) {
        switch ($entry_status) {
            case AssignmentStatus::PATIENT_GOT_ASSIGNMENT:
                return PatientStatus::PATIENT_GOT_ASSIGNMENT;
            case AssignmentStatus::PATIENT_EDITED_ASSIGNMENT:
                return PatientStatus::PATIENT_EDITED_ASSIGNMENT;
            case AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT:
                return PatientStatus::PATIENT_FINISHED_ASSIGNMENT;
            case AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT:
                return PatientStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            case AssignmentStatus::PATIENT_RATED_COMMENT:
                return PatientStatus::PATIENT_RATED_COMMENT;
            case AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT:
                return PatientStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
        }

        return PatientStatus::UNKNOWN;
    }

}