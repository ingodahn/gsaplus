<?php

namespace App\Console\Commands;

use App\Models\AssignmentType;
use App\Patient;
use App\Assignment;

use App\TestSetting;
use Jenssegers\Date\Date;

use App\Models\InfoModel;
use Illuminate\Console\Command;

use Illuminate\Database\Eloquent\Collection;
use Log;

/**
 * This command is only needed for testing and may be removed later on.
 *
 * It clears all unnecessary writing dates.
 *
 * Class ClearFutureWritingDates
 * @package App\Console\Commands
 */
class ClearDistantData extends Command
{
    const OPTION_PATIENT = 'patient';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsa:clear-distant-data 
                        {--'.self::OPTION_PATIENT.'= : limit action to a specific patient}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes inconsistent data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = TestSetting::first();

        // unset test date
        Date::setTestNow();

        if ($settings && $settings->test_date) {
            Date::setTestNow($settings->test_date->max(Date::now()));
        }

        Log::info('using '.Date::now()->format('d.m.Y').' to clear data');

        $patients = new Collection;

        if ($this->option(self::OPTION_PATIENT)) {
            $patients->push(Patient::whereName($this->option(self::OPTION_PATIENT))->first());
        } else {
            $patients = Patient::all();
        }

        // !! the command has to use the actual date (not the test date)
        // -> don't attach middleware when entering route in routes.php
        foreach ($patients as $patient) {
            // select current assignment
            // the collection starts with index 0
            // => index ($patient_week - 1) selects current assignment
            // => index ($patient_week) selects next assignment
            $index_for_next_week = max( $patient->patient_week, 0 );
            // select all assignments surpassing the current assignment
            $distant_assignments = $patient->ordered_assignments()->slice($index_for_next_week);
            // set writing dates to null
            $this->removeData($distant_assignments, $patient);
            // reset intervention ended flag, etc. ...
            $this->resetAttributesOfPatient($patient);

            $patient->push();
        }
    }

    protected function removeData(Collection $assignments, Patient $patient) {
        foreach ($assignments as $assignment) {
            // only clear writing date of assignments surpassing the next assignment
            if ((max($patient->patient_week, 0) + 2) <= $assignment->week) {
                $this->removeWritingDate($assignment);
            }

            $this->removeCommentAndReplies($assignment);
            $this->resetAttributesOfAssignment($assignment);

            $assignment->push();
        }
    }

    protected function removeWritingDate(&$assignment) {
        if ($assignment->writing_date) {
            $assignment->writing_date = null;
            $assignment->save();

            $this->info("Cleared writing date".$this->getAssignmentContextString($assignment));
        }
    }

    protected function removeCommentAndReplies(Assignment &$assignment) {
        if ($assignment->comment) {
            $comment = $assignment->comment;


            if ($comment->comment_reply) {
                $comment->comment_reply()->delete();

                $this->info("Cleared comment reply".$this->getAssignmentContextString($assignment));
            }

            $assignment->comment()->delete();

            $this->info("Cleared comment".$this->getAssignmentContextString($assignment));
        }
    }

    protected function resetAttributesOfAssignment(Assignment &$assignment) {
        switch ($assignment->type) {
            case AssignmentType::SITUATION_SURVEY:
                foreach ($assignment->situations as $situation) {
                    $this->clearSituation($situation);
                }

                $this->info("Emptied situations".$this->getAssignmentContextString($assignment));
                break;
            case AssignmentType::TASK:
                $this->clearTask($assignment);
                $this->info("Reset task".$this->getAssignmentContextString($assignment));
                break;
        }

        if ($assignment->survey) {
            $assignment->survey()->delete();
            $this->info("Removed survey".$this->getAssignmentContextString($assignment));
        }

        $assignment->dirty = false;
        $assignment->notified_due = false;

        $this->info("Reset assignment attributes".$this->getAssignmentContextString($assignment));

        $assignment->push();
    }

    protected function resetAttributesOfPatient(Patient &$patient) {
        /* uncomment to reset notes (for ALL patients)
        $patient->notes_of_therapist = "";
        $patient->personal_information = "";
        */

        if ($patient->assignment_day_changes_left === 0) {
            $patient->assignment_day_changes_left = 2;
        }

        if ($patient->last_activity && $patient->last_activity->isFuture()) {
            $patient->last_activity = Date::now();
        }

        if ($patient->last_login && $patient->last_login->isFuture()) {
            $patient->last_login = Date::now();
        }

        if ($patient->intervention_ended_on && $patient->intervention_ended_on->isFuture()) {
            $patient->intervention_ended_on = null;
        }

        $this->info("Reset attributes".$this->getPatientContextString($patient));
    }

    protected function clearSituation(&$situation) {
        $situation->description = "";
        $situation->expectation = "";
        $situation->my_reaction = "";
        $situation->their_reaction = "";

        $situation->save();
    }

    protected function clearTask(&$task) {
        $task->answer = "";
        $task->save();
    }

    protected function getAssignmentContextString($assignment) {
        return  " for patient ".$assignment->patient->name." (week ".$assignment->week.")\n";
    }

    protected function getPatientContextString($patient) {
        return " for ".$patient->name."\n";
    }

}
