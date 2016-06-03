<?php

namespace App;

use App\Models\UserRole;
use App\Models\AssignmentStatus;
use App\Models\PatientStatus;

use Illuminate\Database\Eloquent\Collection;
use Jenssegers\Date\Date;

use Log;

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
        'notified_of_intervention_end',
        'confirmed_registration'
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
        'intervention_ended_on'
    ];

    protected $dynamic_attributes = [
        'patient_status',
        'status_of_next_assignment',
        'patient_week',
        'overdue',
        'next_writing_date'
    ];

    protected $casts = [
        'notified_of_intervention_end' => 'boolean',
        'confirmed_registration' => 'boolean'
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

    public function getNextWritingDateAttribute() {
        return $this->next_assignment() ? $this->next_assignment()->writing_date : null;
    }

    public function setAssignmentDayAttribute($assignment_day) {
        $current_assignment = $this->current_assignment();

        if ($current_assignment && $current_assignment->week < 12) {
            // day is changed during intervention period

            // leave patient at least 5 days time to complete the current assignment
            $next_writing_date = Date::now()->next($assignment_day);

            if (Date::now()->startOfDay()->diffInDays($next_writing_date) < config('gsa.buffer_between_assignments')) {
                $next_writing_date->addWeek();
            }

            $next_assignment = $this->next_assignment();
            $next_assignment->writing_date = $next_writing_date;

            $next_assignment->save();
        }

        $this->attributes['assignment_day'] = $assignment_day;
    }

    public function setDateFromClinicsAttribute($date_from_clinics) {
        $first_assignment = $this->first_assignment();

        if ($this->patient_week() < 1 && $first_assignment && $date_from_clinics !== null) {
            // first writing date should lie in the future
            if ($date_from_clinics->isPast()) {
                // use current date for the calculation if the date is in the past
                $reference_date = Date::now();
            } else {
                // use date from clinics if it is in the future
                $reference_date = $date_from_clinics->copy();
            }

            $reference_date = $reference_date->startOfDay();

            // leave patient at least 5 days time to complete the current assignment
            $next_writing_date = $reference_date->copy()->next($this->assignment_day);

            if ($reference_date->diffInDays($next_writing_date) < config('gsa.buffer_between_assignments')) {
                $next_writing_date->addWeek();
            }

            $first_assignment->writing_date = $next_writing_date;
            $first_assignment->save();
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
     * Returns the day of the previous assignment.
     *
     * @return Date the day of the previous assignment
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
        return $this->assignment_for_week(max($this->patient_week() + 1, 1));
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
            $status = $assignment->assignment_status;

            if ($status === AssignmentStatus::ASSIGNMENT_WILL_BECOME_DUE_SOON ||
                $status < AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT) {
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
        if ($this->date_from_clinics === null || $this->date_from_clinics->isFuture()) {
            // patient hasn't left the clinic
            return -1;
        } else if (Date::now()->gte($this->date_from_clinics) &&
                    ($this->first_assignment() === null ||
                    $this->first_assignment()->writing_date === null ||
                    Date::now()->lt($this->first_assignment()->writing_date))) {
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
            return PatientStatus::COLLABORATION_ENDED;
        }

        $patient_week = $this->patient_week();

        switch ($patient_week) {
            case -1:
                // patient resides in clinic
                if ($this->date_from_clinics !== null) {
                    // date of departure is set
                    return PatientStatus::DATE_OF_DEPARTURE_SET;
                } else {
                    // date of departure isn't set but patient is registered
                    return PatientStatus::REGISTERED;
                }
            case 0:
                return PatientStatus::PATIENT_LEFT_CLINIC;
            case 12:
                if ($this->current_assignment()->writing_date->copy()->addWeek()->isPast()) {
                    return PatientStatus::INTERVENTION_ENDED;
                }
            default:
                return AssignmentStatus::to_patient_status($this->current_assignment()->status());
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
     *      -> with survey results
     *      -> with comment
     *          -> and commentReply
     *
     * @return array an info that contains descriptions of all possible sub relations
     */
    public function all_info() {
        return $this->info_with('therapist',
                           'assignments.situations',
                           'assignments.survey.',
                           'assignments.comment.comment_reply');
    }

}
