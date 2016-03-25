<?php

namespace App;

use App\Models\AssignmentStatus;
use App\Models\AssignmentType;

use Jenssegers\Date\Date;

class SituationSurvey extends Assignment
{

    protected static $singleTableType = AssignmentType::SITUATION_SURVEY;

    public function situations() {
        return $this->hasMany('App\Situation');
    }

    public function status() {
        $status = parent::status();

        // situation survey is always defined and therapist doesn't
        // save anything - the survey is created automatically
        // -> no need to check for this cases

        $situations = $this->situations;

        if ($this->dirty === false) {
            // did the user provide any answer?
            $partially_answered = false;

            if ($this->$situations !== null) {
                foreach ($situations as $situation) {
                    if ($situation->description
                        || $situation->expectation
                        || $situation->my_reaction
                        || $situation->their_reaction) {
                        // user provided some answers
                        $partially_answered = true;
                        break;
                    }
                }
            }

            if ($partially_answered) {
                // dirty is false and an answer is provided
                // -> patient sent in the answer
                return AssignmentStatus::PATIENT_FINISHED_ASSIGNMENT;
            } else if (Date::now()->gt(
                $this->patient->previous_assignment_day()
                    ->addDays(config('gsa.reminder_period_in_days')))) {
                // patient was reminded by system and didn't submit any text
                // TODO: check if this is really the case! -> check reminders
                return AssignmentStatus::SYSTEM_REMINDED_OF_ASSIGNMENT;
            }
        }

        return $status;
    }

}
