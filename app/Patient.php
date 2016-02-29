<?php

namespace App;

use App\Models\AssignmentStatus;

use Carbon\Carbon;
use App\Models\PatientStatus;

class Patient extends User
{

    protected static $singleTableType = 'patient';

    protected static $persisted = ['code', 'assignment_day', 'assignment_day_changes_left', 'date_from_clinics',
        // patient status should be determined - not cached
        // 'patient_status',
        'last_activity', 'personal_information', 'notes_of_therapist', 'registration_date', 'therapist_id' ];

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
        $assignment_count = $this->ordered_assignments()->count();
        $overdue = $this->assignments()->whereDoesntHave('response')->count();

        return $assignment_count > 0 ? $overdue / $assignment_count : 0;
    }

    /**
     * Nummer der Woche der Intervention (0...13) oder -1 (falls der Patient
     * noch in der Klinik ist)
     */
    public function patient_week()
    {
        // -> Ausgangsdatum: Entlassungsdatum
        // -> nächster Schreibtag: in der ersten Woche danach
        // -> 0-te Woche ist die bis zum ersten Schreibtag
        // -> dann die Differenz vom ersten Schreibtag bis heute berechnen
        //    (in Wochen - nat. hächstens 12 Wochen zählen, ansonsten
        //    ist die Intervention bereits vorbei)
        //

        // Anmerkung: es muss noch kein assignment existieren, also auch nicht mit arbeiten
        // die Wochen zählen, nicht die Aufgaben

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
            // n+1-te Woche bei n Wochen Differenz
            // Bsp. 1 Tag nach dem ersten Schreibtag -> 0 Wochen Differenz -> 1-te Woche
            return Carbon::now()->startOfDay()->diffInWeeks($this->first_assignment_day()) + 1;
        }
    }

    /**
     * Status des Patienten
     */
    public function status()
    {
        // Aufgabe wird am Schreibtag bekannt gegeben (gilt für alle Aufgaben)
        // der Patient sollte die Aufgabe am selben Tag beantworten
        // ansonsten bekommt er eine Erinnerung vom System (5 Tage danach - konfigurierbar)
        // der Patient kann dann bis zum nächsten Schreibtag antworten
        // am nächsten Schreibtag gilt die Aufgabe als versäumt

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
                    // shouldn't happen... but...
                    return $is_first_week ? PatientStatus::DATE_OF_DEPARTURE_SET :
                        PatientStatus::UNKNOWN;
                } else {
                    return AssignmentStatus::to_patient_status($actual_assignment->status());
                }
        }
    }

}
