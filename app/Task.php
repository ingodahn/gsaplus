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

    public function status() {
        $status = parent::status();

        if ($this->problem === "" && $status !== AssignmentStatus::ASSIGNMENT_IS_NOT_REQUIRED) {
            // therapist didn't provide a problem
            return AssignmentStatus::ASSIGNMENT_IS_NOT_DEFINED;
        }

        return $status;
    }

    public function getPartiallyAnsweredAttribute() {
        return $this->answer && $this->answer != '';
    }

}
