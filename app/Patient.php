<?php

namespace App;

use App\Models\UserRole;
use App\Models\AssignmentStatus;
use App\Models\PatientStatus;

use Jenssegers\Date\Date;

class Patient extends User
{

    protected static $singleTableType = UserRole::PATIENT;

    protected static $persisted = ['code',
        'assignment_day',
        'assignment_day_changes_left',
        'date_from_clinics',
        'last_activity',
        'personal_information',
        'notes_of_therapist',
        'registration_date',
        'therapist_id',
        'intervention_ended_on',
        'last_login',
    ];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['therapist_id'];

    protected $dates = [
        'registration_date',
        'date_from_clinics',
        'last_activity',
        'last_login'
    ];

    public $info_methods = [
        'status',
        'status_of_next_assignment',
        'patient_week',
        'overdue'
    ];

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getDateFromClinicsAttribute($date) {
        return new Date($date);
    }

    public function getLastActivityAttribute($date) {
        return new Date($date);
    }

    public function getRegistrationDateAttribute($date) {
        return new Date($date);
    }

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

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $therapist = $this->therapist ? $this->therapist->name : $this->info_null_string;

        $info = array_add($info, $this->class_name(). '.therapist', $therapist);

        return $info;
    }

    /**
     * Returns an ordered list of all assignments (including undefined assignments).
     *
     * @return an ordered list of all assignments (including undefined assignments)
     */
    public function ordered_assignments() {
        return $this->assignments->sortBy('week');
    }

    /**
     * Returns the day of the first assignment (a Date object).
     *
     * @return the day of the first assignment (a Date object)
     */
    public function first_assignment_day () {
        return $this->date_from_clinics->copy()->startOfDay()
            ->endOfWeek()->next($this->assignment_day);
    }

    /**
     * Returns the day of the last assignment (a Date object).
     *
     * @return the day of the last assignment (a Date object)
     */
    public function last_assignment_day() {
        return Date::now()->startOfDay()->previous($this->assignment_day);
    }

    /**
     * Returns the current assignment (assigned on the recent assignment day).
     *
     * @return the current assignment
     */
    public function current_assignment() {
        return $this->assignment_for_week($this->patient_week());
    }

    /**
     * Returns the next assignment (for the next assignment day).
     */
    public function next_assignment() {
        return $this->assignment_for_week($this->patient_week() + 1);
    }

    /**
     * Returns the assignment for the given week.
     */
    public function assignment_for_week($week) {
        return $this->ordered_assignments()->get($week - 1);
    }

    /**
     * Returns the week the intervention ended or null, if the intervention didn't end yet.
     *
     * @return the week the intervention ended or null (if intervention is still running)
     */
    public function intervention_ended_in_week() {
        return $this->intervention_ended_on !== null ? $this->week_for_date($this->intervention_ended_on) : null;
    }

    /**
     * Returns the number of past assignments (including the current assignment).
     *
     * @return the number of past assignments (including the current assignment)
     */
    public function past_assignments() {
        return $this->assignments()->where('week', '<=', $this->patient_week());
    }

    /**
     * Returns the number of past assignments that have a response (including the current assignment)
     *
     * @return the number of past assignments that have a response
     *
     * TODO: check for patient text (? - only for current assignment?)
     */
    public function past_assignments_without_response() {
        return $this->past_assignments()->whereDoesntHave('response');
    }

    /**
     * Prozentsatz der versäumten Tagebucheinträge
     */
    public function overdue()
    {
        // TODO: alle Aufgaben bis zur aktuellen Aufgabe zählen,
        // da meistens ebenso zukünftige Aufgaben eixistieren
        // (Ingo: am Anfang werden 12 leere Aufgaben angelegt)

        $number_of_past_assignments = $this->past_assignments()->count();

        return $number_of_past_assignments > 0 ?
                    $this->past_assignments_without_response()->count() / $number_of_past_assignments : 0;

    }

    /**
     * Nummer der Woche der Intervention (0...13) oder -1 (falls der Patient
     * noch in der Klinik ist)
     */
    public function patient_week() {
        return $this->week_for_date(Date::now());
    }

    /**
     * Returns
     * - -1, if the patient resided in the clinic (at the given time)
     * - 0, if the patient left the clinic, but no assignment ist defined yet (at the given time)
     * - the number of weeks lying between the first assignment day and the given date, otherwise
     *
     * @param Date|null $date
     *          the reference date
     *
     * @return int
     *          the week of intervention (at the given time)
     */
    public function week_for_date(Date $date = null) {
        if (is_null($date)) {
            $date = Date::now();
        }

        // -> Ausgangsdatum: Entlassungsdatum
        // -> nächster Schreibtag: in der ersten Woche danach
        // -> 0-te Woche ist die bis zum ersten Schreibtag
        // -> dann die Differenz vom ersten Schreibtag bis zum gegebenen Datum berechnen
        //    (in Wochen - nat. hächstens 12 Wochen zählen, ansonsten
        //    war die Intervention bereits vorbei)
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
        } else if ($date->between($this->date_from_clinics, $this->first_assignment_day())) {
            // 0-te Woche falls der erste Schreibtag in der Zukunft liegt
            // und der Patient bereits entlassen wurde
            return 0;
        } else {
            // n+1-te Woche bei n Wochen Differenz
            // Bsp. 1 Tag nach dem ersten Schreibtag -> 0 Wochen Differenz -> 1-te Woche
            return $date->copy()->startOfDay()->diffInWeeks($this->first_assignment_day()) + 1;
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

        if ($this->intervention_ended_on !== null) {
            return PatientStatus::INTERVENTION_ENDED;
        }

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
                // patient has left the clinic but hasn't received an assignment yet
                return PatientStatus::REGISTERED;
            default:
                // get the current assignment (assignment count starts with 0!)
                $current_assignment = $this->current_assignment();

                if ($current_assignment === null) {
                    // shouldn't happen... but...
                    return ($patient_week === 1) ? PatientStatus::DATE_OF_DEPARTURE_SET :
                        PatientStatus::UNKNOWN;
                } else {
                    return AssignmentStatus::to_patient_status($current_assignment->status());
                }
        }
    }

    /**
     * Returns the status of the next assignment.
     */
    public function status_of_next_assignment() {
        if ($this->intervention_ended_on !== null || $this->patient_week() === 12) {
            return AssignmentStatus::ASSIGNMENT_IS_NOT_REQUIRED;
        } else {
            if ($this->next_assignment() === null) {
                return AssignmentStatus::ASSIGNMENT_IS_NOT_DEFINED;
            } else {
                return $this->next_assignment()->status();
            }
        }
    }

}
