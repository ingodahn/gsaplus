<?php

namespace App;

use App\Models\UserRole;
use App\Models\AssignmentStatus;
use App\Models\PatientStatus;

use Illuminate\Database\Eloquent\Collection;
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
        'date_of_last_reminder',
        'last_login',
    ];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['therapist_id',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'is_random',
        'type'
    ];

    protected $dates = [
        'registration_date',
        'date_from_clinics',
        'last_activity',
        'last_login',
        'intervention_ended_on',
        'date_of_last_reminder'
    ];

    protected $dynamic_attributes = [
        'patient_status',
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
        return $date === null ? null : new Date($date);
    }

    public function getLastActivityAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getRegistrationDateAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getPatientStatusAttribute() {
        return $this->status();
    }

    public function getStatusOfNextAssignmentAttribute() {
        return $this->status_of_next_assignment();
    }

    public function getPatientWeekAttribute() {
        return $this->patient_week();
    }

    public function getOverdueAttribute() {
        return $this->overdue();
    }

    public function setAssignmentDayAttribute($assignment_day) {
        $current_assignment = $this->current_assignment();

        if ($current_assignment && $current_assignment->week < 12) {
            // day is changed during intervention period

            // leave patient at least one week time complete the current assignment
            $next_writing_date = Date::now()->next($this->assignment_day)->addWeek();

            $next_assignment = $this->next_assignment();
            $next_assignment->writing_date = $next_writing_date;
        }

        $this->attributes['assignment_day'] = $assignment_day;
    }

    public function setDateFromClinicsAttribute($date_from_clinics) {
        $first_assignment = $this->first_assignment();

        if ($this->patient_week() < 1 && $first_assignment) {
            // first writing date should lie in the future
            if ($date_from_clinics->isPast()) {
                // use current date for the calculation if the date is in the past
                $reference_date = Date::now();
            } else {
                // use date from clinics if it is in the future
                $reference_date = $date_from_clinics->copy();
            }

            // give patient at least a week before the first assignment starts
            $first_assignment->writing_date = $reference_date->copy()->startOfDay()
                ->addWeek()->next($this->assignment_day);
        }

        $this->attributes['date_from_clinics'] = $date_from_clinics;
    }

    /**
     * Relationship to the patients assignments (all - independent of state) including
     * the results of the situation survey. Please use $patient->assignments
     * to access the collection.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany all assignments including the
     *          results of the situation survey
     */
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    /**
     * Relationship to the responsible therapist (if set). Please use $patient->therapist
     * to access the therapist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo the responsible
     *          therapist (if set)
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist', 'therapist_id');
    }

    /**
     * Returns an ordered collection of all assignments that also
     * includes the situation survey.
     *
     * @return Collection an ordered list of all assignments that also
     * includes the situation survey
     */
    public function ordered_assignments() {
        return $this->assignments->sortBy('week');
    }

    /**
     * Returns the day of the first assignment.
     *
     * @return Date the day of the first assignment
     */
    public function first_assignment_day() {
        return $this->first_assignment()->writing_date ?: null;
    }

    /**
     * Returns the day of the last assignment.
     *
     * @return Date the day of the last assignment
     */
    public function previous_assignment_day() {
        return $this->current_assignment()->writing_date;
    }

    /**
     * Returns the weeks assignment day (in the past or future).
     *
     * @param $week
     *          the week
     * @return Date the given weeks assignment day
     */
    public function assignment_day_for_week($week) {
        return $this->assignment_for_week($week)->writing_date ?: null;
    }

    /**
     * Returns the first assignment (the situation survey).
     *
     * @return SituationSurvey the first assignment
     */
    public function first_assignment() {
        return $this->ordered_assignments()->get(0);
    }

    /**
     * Returns the latest assignment with writing_date < Date::now()
     * (the current assignment).
     *
     * @return Assignment the latest assignment with writing_date < Date::now()
     */
    public function current_assignment() {
        return $this->latest_assignment_for_date(Date::now());
    }

    /**
     * Returns the next assignment (for the next assignment day).
     *
     * @return Assignment the next assignment (for the next assignment day).
     */
    public function next_assignment() {
        return $this->assignment_for_week($this->patient_week() + 1);
    }

    /**
     * Returns the latest assignment with writing_date < $date.
     *
     * @param $date
     *          the upper bound (for writing_date)
     *
     * @return Assignment the latest assignment with writing_date < $date
     */
    public function latest_assignment_for_date($date) {
        // all() loads all entries -> use orderBy and grab the first result
        return $this->assignments()->whereDate('writing_date', '<=', $date)
                ->orderBy('writing_date', 'desc')->first();
    }

    /**
     * Returns the assignment for the given week.
     *
     * @return Assignment the assignment for the given week
     */
    public function assignment_for_week($week) {
        return $this->ordered_assignments()->get($week - 1);
    }

    /**
     * Returns past assignments (including the current assignment and the
     * situation survey).
     *
     * @return Collection past assignments (including the current assignment and
     * the situation survey)
     */
    public function past_assignments() {
        return $this->assignments()->where('week', '<=', $this->patient_week())->get();
    }

    /**
     * Prozentsatz der versäumten Tagebucheinträge
     *
     * @return float Prozentsatz der versäumten Tagebucheinträge
     */
    public function overdue()
    {
        $overdue = 0;
        $past_assignments = $this->past_assignments();

        foreach ($past_assignments as $assignment) {
            if ($assignment->assignment_status === AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT) {
                $overdue++;
            }
        }

        return $past_assignments->count() > 0 ? $overdue / $past_assignments->count() : 0;
    }

    /**
     * Nummer der Woche der Intervention (0...12) oder -1 (falls der Patient
     * noch in der Klinik ist).
     *
     * Returns
     * - -1, if the patient resided in the clinic (at the given time)
     * - 0, if the patient left the clinic, but no assignment ist defined yet (at the given time)
     * - the number of assignments with writing_date <= now <= (next_writing_date || writing_date->addWeek()),
     * if such an assignment exists
     * - null otherwise
     *
     * @return int Nummer der Woche der Intervention (0...12) oder -1 (falls der Patient
     * noch in der Klinik ist)
     */
    public function patient_week() {
        // -> Ausgangsdatum: Entlassungsdatum / Tag an dem das Entlassungsdatum eingetragen wird
        // (wenn das Datum in der Vergangenheit liegt)
        //
        // -> nächster Schreibtag: mindestens eine Woche später
        // -> 0-te Woche ist die bis zum ersten Schreibtag
        if ($this->date_from_clinics === null || $this->date_from_clinics->isFuture()) {
            // patient hasn't left the clinic
            return -1;
        } else if (Date::now()->gte($this->date_from_clinics) &&
                    Date::now()->lt($this->first_assignment()->writing_date)) {
            // 0-te Woche falls der erste Schreibtag in der Zukunft liegt
            // und der Patient bereits entlassen wurde
            return 0;
        } else {
            $current_assignment = $this->current_assignment();

            return $current_assignment ? $current_assignment->week : null;
        }
    }

    /**
     * Status des Patienten
     *
     * @return string Status des Patienten
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
            case 0:
                // patient resides in clinic
                if ($this->date_from_clinics !== null) {
                    // date of departure is set and lies in the future
                    return PatientStatus::DATE_OF_DEPARTURE_SET;
                } else {
                    // date of departure isn't set but patient is registered
                    return PatientStatus::REGISTERED;
                }
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
     *
     * @return string status of the next assignment
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

    /**
     * An info that contains descriptions of all possible sub relations.
     *
     * Included:
     * - therapist
     * - assignments
     *      -> with all situations (if assignment is a situation survey)
     *      -> with survey
     *      -> with phq4 and wai
     *      -> with comment
     *          -> and commentReply
     *
     * @return array an info that contains descriptions of all possible sub relations
     */
    public function all_info() {
        return $this->info_with('therapist',
                           'assignments.situations',
                           'assignments.survey.phq4',
                           'assignments.survey.wai',
                           'assignments.comment.comment_reply');
    }

}
