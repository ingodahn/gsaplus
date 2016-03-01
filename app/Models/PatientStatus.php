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
        "P000"=>"P000: Unbekannt",
        "P020"=>"P020: Registriert",
        "P025"=>"P025: Entlassungsdatum erfasst",
        "P030"=>"P030: Aufgabe erhalten",
        "P040"=>"P040: Aufgabe bearbeitet",
        "P045"=>"P045: Aufgabe gemahnt",
        "P050"=>"P050: Aufgabe abgeschickt",
        "P060"=>"P060: Aufgabe kommentiert",
        "P065"=>"P065: Aufgabenkommentar bewertet",
        "P130"=>"P130: Mitarbeit beendet",
        "P140"=>"P140: Interventionszeit beendet"
    );

}
?>