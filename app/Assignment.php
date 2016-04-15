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

    protected $dates = ['created_at', 'updated_at', 'writing_date'];

    protected $casts = ['dirty' => 'boolean'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['patient_id',
        'task_template_id',
        'created_at',
        'updated_at',
        'is_random',
        'type'];

    protected $dynamic_attributes = [
        'assignment_status',
        'partially_answered',
        'system_reminded_of_assignment'
    ];

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

    /**
     * Relationship to the surveys answers. Please use $assignment->survey
     * to access the answers.
     */
    public function survey() {
        return $this->hasOne('App\Survey', 'assignment_id');
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
        return $date === null ? null : new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getAssignedOnAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getWritingDateAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getAssignmentStatusAttribute() {
        return $this->status();
    }

    public function getSystemRemindedOfAssignmentAttribute() {
        $not_saved_or_empty = (!$this->partially_answered || $this->dirty);

        $system_reminded_of_assignment = ($not_saved_or_empty && $this->is_past_assignment) ||
                                            ($this->is_current_assignment &&
                                            $this->patient->date_of_last_reminder &&
                                            $this->patient->date_of_last_reminder->gte($this->writing_date));

        return $system_reminded_of_assignment;
    }

    public function getIsCurrentAssignmentAttribute() {
        $current_assignment = $this->patient->current_assignment();

        return ($current_assignment && $current_assignment->week == $this->week);
    }

    public function getIsPastAssignmentAttribute() {
        $current_assignment = $this->patient->current_assignment();

        return ($current_assignment && ($current_assignment->week > $this->week));
    }

    /**
     * Status der Aufgabe
     *
     * @return string Status der Aufgabe
     */
    public function status() {
        if ($this->patient->intervention_ended_on !== null) {
            // if intervention end date is set and writing date is null
            // -> assignment hasn't been assigned yet and is not required
            // if writing date is set: check if writing date is greater than
            // intervention end date
            if  ($this->writing_date === null
                    || $this->patient->intervention_ended_on->lt($this->writing_date)) {
                return AssignmentStatus::ASSIGNMENT_IS_NOT_REQUIRED;
            }
        } else if ($this->comment !== null) {
            if ($this->comment->comment_reply !== null) {
                // patient rated therapists comment
                return AssignmentStatus::PATIENT_RATED_COMMENT;
            } else {
                // therapist provided comment to patients answer
                return AssignmentStatus::THERAPIST_COMMENTED_ASSIGNMENT;
            }
        } else if ($this->system_reminded_of_assignment){
            // patient was reminded by system and didn't submit a text
            return AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
        } else if ($this->partially_answered) {
            if ($this->dirty) {
                // patient has provided an answer but didn't save it
                return AssignmentStatus::PATIENT_EDITED_ASSIGNMENT;
            } else {
                // patient sent in the answer
                return AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT;
            }
        } else if ($this->patient->patient_week === $this->week) {
            // patient didn't edit the assignment
            return AssignmentStatus::PATIENT_GOT_ASSIGNMENT;
        } else if ($this->patient->patient_week < $this->week) {
            // therapist entered text of assignment (or has
            // used text from template) and the assignment lies
            // in the future (patient didn't get it yet)
            return AssignmentStatus::THERAPIST_SAVED_ASSIGNMENT;
        }

        return AssignmentStatus::UNKNOWN;
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
        return $this->info_with('situations',
            'comment.comment_reply',
            'survey.phq4',
            'survey.wai');
    }
}
