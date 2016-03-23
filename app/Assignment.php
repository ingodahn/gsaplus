<?php

namespace App;

use App\Models\AssignmentStatus;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class Assignment extends InfoModel
{

    protected $dates = ['created_at', 'updated_at', 'assigned_on'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['patient_id', 'assignment_template_id'];

    /**
     * Relationship to the underlying template (the assignment is
     * based on). Please use $assignment->assignment_template to
     * access the template.
     */
    public function assignment_template()
    {
        return $this->belongsTo('App\AssignmentTemplate');
    }

    /**
     * Relationship to the patient (who should answer the assignment).
     * Please use $assignment->patient to access the patient.
     */
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    /**
     * Relationship to the therapists comment. Please use $assignment->comment
     * to access the comment.
     */
    public function comment()
    {
        return $this->hasOne('App\Comment');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $template_name = $this->assignment_template ? $this->assignment_template->name : $this->info_null_string;
        $patient_name = $this->patient ? $this->patient->name : $this->info_null_string;

        $template_key_name = 'assignment_template';
        $template_key_name = $this->info_camel_case ? camel_case($template_key_name) : $template_key_name;

        $info = array_add($info, $this->class_name() .'.'. $template_key_name, $template_name);
        $info = array_add($info, $this->class_name() .'.patient', $patient_name);

        return $info;
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
     *
     * @return string Status der Aufgabe
     */
    public function status() {
        if ($this->patient->intervention_ended_on !== null &&
                $this->patient->intervention_ended_in_week() <= $this->week) {
            return AssignmentStatus::ASSIGNMENT_IS_NOT_REQUIRED;
        }

        if ($this->comment === null) {
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
            if ($this->comment->comment_reply !== null) {
                // patient rated therapists comment
                return AssignmentStatus::PATIENT_RATED_COMMENT;
            } else {
                // therapist provided comment to patients answer
                return AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            }
        }
    }

}
