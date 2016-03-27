<?php

namespace App;

use App\Models\AssignmentStatus;
use App\Models\AssignmentType;

use Jenssegers\Date\Date;

class Task extends Assignment
{

    protected static $singleTableType = AssignmentType::TASK;

    protected static $persisted = ['problem', 'answer', 'task_template_id'];

    /**
     * Relationship to the underlying template (the assignment is
     * based on). Please use $task->task_template to
     * access the template.
     */
    public function task_template()
    {
        return $this->belongsTo('App\TaskTemplate');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $template_name = $this->task_template ? $this->task_template->name : $this->info_null_string;
        $template_key_name = $this->info_camel_case ? camel_case('task_template') : 'task_template';

        $info = array_add($info, $this->class_name() .'.'. $template_key_name, $template_name);

        return $info;
    }

    public function status() {
        $status = parent::status();

        if ($this->problem === null) {
            return AssignmentStatus::ASSIGNMENT_IS_NOT_DEFINED;
        } else if ($this->patient->patient_week() < $this->week) {
            // therapist entered text of assignment (or has
            // used text from template) and the assignment lies
            // in the future (patient didn't get it yet)
            return AssignmentStatus::THERAPIST_SAVED_ASSIGNMENT;
        }

        if ($this->dirty === false && $this->answer !== '') {
            // dirty flag is false and an answer is provided
            // -> patient finished the assignment
            return AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT;
        } else if (Date::now()->gt(
            $this->patient->previous_assignment_day()
                ->addDays(config('gsa.reminder_period_in_days')))) {
            // dirty is true, patient didn't finish the assignment
            // and the reminder period is over
            // -> patient was reminded by system to sent in the answer
            // TODO: check if this is really the case! -> check reminders
            return AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
        }

        return $status;
    }

}
