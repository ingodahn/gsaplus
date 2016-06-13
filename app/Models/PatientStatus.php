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
    const PATIENT_LEFT_CLINIC = "P028";

    const PATIENT_GOT_ASSIGNMENT = "P030";
    const PATIENT_EDITED_ASSIGNMENT = "P040";
    const ASSIGNMENT_WILL_BECOME_DUE_SOON = "P045";
    const PATIENT_FINISHED_ASSIGNMENT = "P050";
    const THERAPIST_COMMENTED_ASSIGNMENT = "P060";
    const PATIENT_RATED_COMMENT = "P065";
    const PATIENT_MISSED_ASSIGNMENT = "P070";

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
        self::PATIENT_LEFT_CLINIC => "Entlassen",
        self::PATIENT_GOT_ASSIGNMENT => "Schreibimpuls erhalten",
        self::PATIENT_EDITED_ASSIGNMENT => "Tagebucheintrag in Bearbeitung und zwischengespeichert",
        self::ASSIGNMENT_WILL_BECOME_DUE_SOON => "Tagebucheintrag in Kürze fällig",
        self::PATIENT_FINISHED_ASSIGNMENT => "Tagebucheintrag abgeschickt",
        self::THERAPIST_COMMENTED_ASSIGNMENT => "Ihr Onlinetherapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung.",
        self::PATIENT_RATED_COMMENT => "Tagebucheintrag und Rückmeldung abgeschlossen",
        self::PATIENT_MISSED_ASSIGNMENT => "Schreibimpuls verpasst",
        self::COLLABORATION_ENDED => "Mitarbeit beendet",
        self::INTERVENTION_ENDED => "Interventionszeit beendet",
        self::UNKNOWN => "Unbekannt"
    );

}
?>
