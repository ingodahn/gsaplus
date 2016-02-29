<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use App\Models\PatientStatus;

class Patient extends User
{

    protected static $singleTableType = 'patient';

    protected static $persisted = ['code', 'assignment_day', 'assignment_day_changes_left', 'date_from_clinics',
        // patient status should be determined - not cached
        // 'patient_status',
        'last_activity', 'personal_information', 'notes_of_therapist', 'registration_date', 'therapist_id' ];

    const INTERVENTION_PERIOD_IN_WEEKS = "12";

    // system reminds patient after ... days to do the assignment
    const REMINDER_PERIOD_IN_DAYS = "5";

    /**
     * Get our assignments (all - independent of state).
     */
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    /**
     * Get the responsible therapist.
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist', 'therapist_id');
    }

    public function ordered_assignments() {
        return $this->assignments->sortBy('assigned_on');
    }

    public function first_assignment_day () {
        return $this->date_from_clinics->copy()->startOfDay()
            ->endOfWeek()->next($this->assignment_day);
    }

    public function last_assignment_day() {
        return Carbon::now()->startOfDay()->previous($this->assignment_day);
    }

    /**
     * Prozentsatz der versäumten Tagebucheinträge
     */
    public function overdue()
    {
        $overdue = $this->assignments()->whereDoesntHave('response')->count();

        return $overdue / $this->ordered_assignments()->count();
    }

    /**
     * Nummer der Woche der Intervention (0...13) oder -1 (falls der Patient
     * noch in der Klinik ist)
     */
    public function patient_week()
    {
        // -> Ausgangsdatum: Entlassungsdatum
        // -> nächster Schreibtag: Anfang der ersten Woche
        // -> 0-te Woche ist die bis zum ersten Schreibtag
        // -> dann die Differenz vom ersten Schreibtag bis heute berechnen
        //    (in Wochen - nat. hächstens 12 Wochen zählen, ansonsten
        //    ist die Intervention bereits vorbei)
        //

        // Anmerkung: es muss noch kein assignment existieren, also auch nicht mit arbeiten
        // die Wochen zählen, nicht die Aufgaben
        // -> System funktioniert, falls etwas schief läuft

        // shouldn't happen... (day is set during registration)
        if ($this->assignment_day === null) {
            return -1;
        }

        if ($this->date_from_clinics === null
            || $this->date_from_clinics->isFuture()) {
            // patient hasn't left the clinic
            return -1;
        } else if (Carbon::now()->between($this->date_from_clinics, $this->first_assignment_day())) {
            // 0-te Woche falls der erste Schreibtag in der Zukunft liegt
            // und der Patient bereits entlassen wurde
            return 0;
        } else {
            // n-te Woche bei n-1 Wochen Differenz (für n>0)
            // Bsp. 1 Tag nach dem ersten Schreibtag -> 0 Wochen Differenz -> 1-te Woche
            return Carbon::now()->startOfDay()->diffInWeeks($this->first_assignment_day()) + 1;
        }
    }

    /**
     * Status des Patienten
     */
    public function status()
    {
        // Status berechnen

        // first assignment is assigned on the week after departure
        // -> check in which week the patient is
        //      (beginning and end of week is the assignment day)
        // -> then check if an assignment exists for that week
        // ...
        // which parameters do matter?

        // Aufgabe wird am Schreibtag bekannt gegeben (für alle Aufgaben)
        // der Patient sollte die Aufgabe am selben Tag beantworten
        // wenn er das nicht tut, bekommt er eine Erinnerung vom System (5 Tage danach)
        // der Patient kann dann bis zum nächsten Schreibtag antworten
        // am nächsten Schreibtag gilt die Aufgabe als versäumt

        // Carbon - helpful methods:
        // - between($1, $2) - determines if the instance is between two others
        // - isSameDay($1)
        // - max($1)
        // - next($day) - Datum des nächsten $day (Wochentag)

        // WICHTIG: erstmal bis P030

        // TODO: code block too huge... clean up
        // reduce db access?

        $patient_week = $this->patient_week();

        switch ($patient_week) {
            case -1:
                // patient resides in clinic
                if ($this->date_from_clinics !== null) {
                    // date of departure is set and lies in the future
                    return PatientStatus::DATE_OF_DEPARTURE_SET;
                } else {
                    // date of departure isn't set but patient is registered
                    return PatientStatus::REGISTERED;
                }
            case 0:
                // patient has left the clinic but hasn't received
                // an assignment yet
                return PatientStatus::REGISTERED;
            default:
                $is_first_week = ($patient_week === 1);

                // get the actual assignment (assignment count starts with 0!)
                $actual_assignment = $this->ordered_assignments()->get($this->patient_week() - 1);

                if ($actual_assignment === null) {
                    // shouldn't happen... but
                    // TODO: what should we return if something went wrong?
                    return $is_first_week ? PatientStatus::DATE_OF_DEPARTURE_SET :
                        null;
                } else {
                    if ($actual_assignment->response === null) {
                        if ($actual_assignment->state === true) {
                            // patient has finished assignment
                            return PatientStatus::PATIENT_FINISHED_ASSIGNMENT;
                        } else if (Carbon::now()->gt($actual_assignment->assigned_on->
                                    copy()->addDays(config('gsa.reminder_period_in_days')))) {
                            // patient was reminded by system and didn't submit any text
                            // TODO: check if this is really the case! -> check reminders
                            return PatientStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
                        } else if ($actual_assignment->patient_text !== null
                            && strcmp($actual_assignment->patient_text, "") !== 0) {
                            // patient has provided some text
                            return PatientStatus::PATIENT_EDITED_ASSIGNMENT;
                        } else {
                            return PatientStatus::PATIENT_GOT_ASSIGNMENT;
                        }
                    } else {
                        if ($actual_assignment->response->rating !== null) {
                            return PatientStatus::PATIENT_RATED_COMMENT;
                        } else {
                            return PatientStatus::THERAPIST_COMMENTED_ASSIGNMENT;
                        }
                    }
                }
        }
    }

}
