<?php

namespace App;

use App\Models\AssignmentStatus;

use App\Models\InfoModel;

use Jenssegers\Date\Date;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Assignment extends InfoModel
{

    use SingleTableInheritanceTrait;

    protected $table = "assignments";

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [SituationSurvey::class, Task::class];

    protected static $persisted = ['dirty', 'week', 'patient_id', 'is_random'];

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['patient_id', 'assignment_template_id'];

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
        return $this->hasOne('App\Comment', 'assignment_id');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $patient_name = $this->patient ? $this->patient->name : $this->info_null_string;
        $comment = $this->comment ? $this->comment->text : $this->info_null_string;

        $info = array_add($info, $this->class_name() .'.patient', $patient_name);
        $info = array_add($info, $this->class_name() .'.comment', $comment);

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

        if ($this->comment !== null) {
            if ($this->comment->comment_reply !== null) {
                // patient rated therapists comment
                return AssignmentStatus::PATIENT_RATED_COMMENT;
            } else {
                // therapist provided comment to patients answer
                return AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            }
        } else if ($this->dirty) {
            return AssignmentStatus::PATIENT_EDITED_ASSIGNMENT;
        } else if ($this->patient->current_assignment() === $this) {
            return AssignmentStatus::PATIENT_GOT_ASSIGNMENT;
        }

        return AssignmentStatus::UNKNOWN;
    }

}
