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
        self::ASSIGNMENT_IS_NOT_DEFINED => "Aufgabe nicht definiert",
        self::THERAPIST_SAVED_ASSIGNMENT => "Aufgabe definiert",
        self::PATIENT_GOT_ASSIGNMENT => "Aufgabe gestellt",
        self::PATIENT_EDITED_ASSIGNMENT => "Aufgabe bearbeitet",
        self::PATIENT_FINISHED_ASSIGNMENT => "Aufgabe abgeschickt",
        self::THERAPIST_COMMENTED_ASSIGNMENT => "Antwort kommentiert",
        self::PATIENT_RATED_COMMENT => "Kommentar bewertet",
        self::SYSTEM_REMINDED_OF_ASSIGNMENT => "Aufgabe überfällig",
        self::ASSIGNMENT_IS_NOT_REQUIRED => "Aufgabe nicht erforderlich"
    );

    public static function to_patient_status($assignment_status) {
        switch ($assignment_status) {
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