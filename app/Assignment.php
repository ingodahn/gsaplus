<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\AssignmentStatus;
use Jenssegers\Date\Date;

class Assignment extends Model
{

    protected $dates = ['created_at', 'updated_at', 'assigned_on'];

    /**
     * Get the underlying template.
     */
    public function assignment_template()
    {
        return $this->belongsTo('App\AssignmentTemplate');
    }

    /**
     * Get the patient who should write an answer.
     */
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    /**
     * Get the response of the therapist.
     */
    public function response()
    {
        return $this->hasOne('App\Response');
    }

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getCreatedAtAttribute($date) {
        return new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return new Date($date);
    }

    public function getAssignedOnAttribute($date) {
        return new Date($date);
    }

    /**
     * Status der Aufgabe
     */
    public function status() {
        if ($this->patient->intervention_ended_on !== null &&
                $this->patient->intervention_ended_in_week() <= $this->week) {
            return AssignmentStatus::ASSIGNMENT_IS_NOT_REQUIRED;
        }

        if ($this->response === null) {
            if ($this->state === true) {
                // patient has finished assignment
                return AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT;
            } else if ($this->assigned_on !== null) {
                if (Date::now()->gt($this->assigned_on->
                        copy()->addDays(config('gsa.reminder_period_in_days')))) {
                    // patient was reminded by system and didn't submit any text
                    // TODO: check if this is really the case! -> check reminders
                    return AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
                } else if ($this->patient_text !== null
                    && strcmp($this->patient_text, "") !== 0) {
                    // patient has provided some text
                    return AssignmentStatus::PATIENT_EDITED_ASSIGNMENT;
                } else {
                    return AssignmentStatus::PATIENT_GOT_ASSIGNMENT;
                }
            }  else if ($this->assignment_text !== null) {
                // therapist entered text of assignment (or has
                // used text from template)
                return AssignmentStatus::THERAPIST_SAVED_ASSIGNMENT;
            } else {
                // assignment exists but nothing has been set
                return AssignmentStatus::ASSIGNMENT_IS_NOT_DEFINED;
            }
        } else {
            if ($this->response->rating !== null) {
                // patient rated therapists comment
                return AssignmentStatus::PATIENT_RATED_COMMENT;
            } else {
                // therapist provided comment to patients response
                return AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            }
        }
    }

}
