<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\AssignmentStatus;
use Carbon\Carbon;

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

    /**
     * TODO: E00-E015 and E100
     */
    public function status() {
        if ($this->response === null) {
            if ($this->state === true) {
                // patient has finished assignment
                return AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT;
            } else if (Carbon::now()->gt($this->assigned_on->
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
        } else {
            if ($this->response->rating !== null) {
                return AssignmentStatus::PATIENT_RATED_COMMENT;
            } else {
                return AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            }
        }
    }

}
