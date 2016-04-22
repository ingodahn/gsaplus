<?php

namespace App\Models;

/**
 * Frage gestellt, wartet auf Eintrag des Patienten
 * Patient hat Text gespeichert, nicht abgeschickt
 * Patient hat den Eintrag eingereicht und wartet auf Kommentar des Therapeuten
 * Tagebuchaufgabe vers�umt
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class PatientStatus
{

    const UNREGISTERED = "P010";
    const REGISTERED = "P020";
    const DATE_OF_DEPARTURE_SET = "P025";

    const PATIENT_GOT_ASSIGNMENT = "P030";
    const PATIENT_EDITED_ASSIGNMENT = "P040";
    const SYSTEM_REMINDED_OF_ASSIGNMENT = "P045";
    const PATIENT_FINISHED_ASSIGNMENT = "P050";
    const THERAPIST_COMMENTED_ASSIGNMENT = "P060";
    const PATIENT_RATED_COMMENT = "P065";

    const COLLABORATION_ENDED = "P130";
    const INTERVENTION_ENDED = "P140";

    const UNKNOWN = "P000";

    /*
     * TODO:
     * - was passiert wenn eine Aufgabe gemahnt und daraufhin abgeschickt wurde?
     */
    public static $STATUS_INFO = array(
        self::REGISTERED => "Registriert",
        self::DATE_OF_DEPARTURE_SET => "Entlassungsdatum erfasst",
        self::PATIENT_GOT_ASSIGNMENT => "Schreibimpuls erhalten",
        self::PATIENT_EDITED_ASSIGNMENT => "Tagebucheintrag bearbeitet",
        self::SYSTEM_REMINDED_OF_ASSIGNMENT => "Tagebucheintrag gemahnt",
        self::PATIENT_FINISHED_ASSIGNMENT => "Tagebucheintrag abgeschickt",
        self::THERAPIST_COMMENTED_ASSIGNMENT => "Tagebucheintrag mit Rückmeldung versehen",
        self::PATIENT_RATED_COMMENT => "Rückmeldung bewertet",
        self::COLLABORATION_ENDED => "Mitarbeit beendet",
        self::INTERVENTION_ENDED => "Interventionszeit beendet"
        self::UNKNOWN => "Unbekannt",
    );

}
?>
