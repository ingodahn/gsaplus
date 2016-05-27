<?php

use App\Patient;
use App\Admin;
use App\Therapist;

use App\Assignment;
use App\TaskTemplate;

use App\Comment;
use App\CommentReply;

use App\Survey;

use App\SituationSurvey;
use App\Task;
use App\Situation;

use App\Models\AssignmentType;

use App\Models\PatientStatus;

use App\Helper;

use Jenssegers\Date\Date;

use Illuminate\Database\Seeder;

abstract class PatientsTableBaseSeeder extends Seeder
{

    /**
     * @var \Faker\Generator $faker
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    /**
     * Fill answers with random texts or "" / add suvery results for random
     * texts (if assignment was saved before).
     *
     * @param Assignment $assignment
     *          the assignment
     * @param $empty
     *          use "" if true, random texts otherwise
     */
    protected function fill_in_answers(Assignment &$assignment, $empty) {
        if ($assignment->type === AssignmentType::SITUATION_SURVEY) {
            $this->fill_in_situations($assignment, $empty);
        } else {
            $assignment->answer = $empty ? "" : $this->faker->realText();
        }

        if (!$assignment->dirty && !$empty) {
            $this->add_survey_results($assignment);
        }
    }

    /**
     * Fill answers with random texts or "".
     *
     * @param SituationSurvey $assignment
     *          the assignment
     * @param $empty
     *          use "" if true, random texts otherwise
     */
    protected function fill_in_situations(Assignment &$assignment, $empty) {
        // TODO: clean up and fix method (creating past assignments)
        if (!$empty && empty($assignment->situations)) {
            $this->add_situation($assignment);
        }

        foreach ($assignment->situations as $situation) {
            $empty ? $situation->description = "" : $this->faker->realText();
            $empty ? $situation->expectation = "" : $this->faker->realText();
            $empty ? $situation->my_reaction = "" : $this->faker->realText();
            $empty ? $situation->their_reaction = "" : $this->faker->realText();

            $situation->save();
        }
    }

    /**
     * Create 12 empty assignments, set date from clinics to null / a registration
     * date and add statistics (login date, etc. ...).
     *
     * @param Patient $patient
     *          the patient
     */
    protected function create_empty_assignments(Patient &$patient) {
        $patient->registration_date = Date::now()->subWeek();
        $patient->date_from_clinics = null;

        $patient->save();

        // create all assignments (all future assignments will be defined)
        for ($week = 1; $week <= 12; $week++) {
            $assignment = ($week == 1) ?
                new SituationSurvey :
                new Task;

            $assignment->week = $week;
            $assignment->dirty = false;

            $patient->assignments()->save($assignment);
        }

        $this->add_statistics($patient);
    }

    /**
     * Create and add a random number of past assignments. Uses
     * @link create_fixed_number_of_past_assignments to create the actual
     * assignments.
     *
     * @param Patient $patient
     *          target (assignments will be created for the given patient)
     * @param $max
     *          the maximum number of assignments
     */
    protected function create_random_number_of_past_assignments(Patient &$patient, $max) {
        // patient has a random number of past assignments
        $this->create_fixed_number_of_past_assignments($patient, rand(1, $max));
    }

    /**
     * Create and add a fixed number of past assignments (including comments, comment
     * replies, ...). Some properties are randomly set (e.g. probability that a saved
     * assignment has a comment = XY).
     *
     * @param Patient $patient
     *          the patient
     * @param $assignment_count
     *          the number of past assignments
     * @param $weeks_till_previous_assignment
     *          time between the previous assignment (in the past) and the current date
     */
    protected function create_fixed_number_of_past_assignments(Patient &$patient, $assignment_count,
                                                                        $weeks_till_previous_assignment = 0) {
        // choose random therapist
        $therapist = Therapist::all()->random();
        $therapist->patients()->save($patient);

        $patient->date_from_clinics = Date::now()
            ->startOfWeek()
            ->subWeeks($assignment_count + 1)
            ->subWeeks($weeks_till_previous_assignment)
            ->addDays(rand(0, 6));

        // set first assignment day
        $first_assignment_day = $patient->date_from_clinics->copy()->startOfDay()
            ->endOfWeek()->next($patient->assignment_day);

        if ($assignment_count === 1 && $first_assignment_day->isFuture()) {
            // the status calculation needs an actual assignment
            // -> adjust time spans and set assignment day in the past
            $first_assignment_day = Date::now()->previous($patient->assignment_day);
            $patient->date_from_clinics = $patient->date_from_clinics->copy()->subWeek();
        }

        // the registration happened before the patient left the clinic
        // registration date -> between 1 day and 3 weeks before date of departure
        // TODO: not always the case
        $patient->registration_date = $this->faker->dateTimeBetween(
            $patient->date_from_clinics->copy()->subWeeks(3),
            $patient->date_from_clinics->copy()->subWeek());

        // create all assignments (all future assignments will be defined)
        for ($week = 1; $week <= 12; $week++) {
            // next writing day is always a week in the future
            // -> not always the case
            // TODO: implement other cases (for testing)
            $writing_date = $first_assignment_day->copy()->addWeeks($week - 1);

            $is_past_assignment = $writing_date->isPast();

            // create the actual assignment
            // random values are generated only if the writing date is in the past
            $assignment = ($week == 1) ?
                ($is_past_assignment ? factory(SituationSurvey::class)->make() : new SituationSurvey) :
                ($is_past_assignment ? factory(Task::class)->make() : new Task);

            // -> date is only specified for past assignments and
            // the next assignment
            if ($writing_date->lte(Date::now()->addWeek())) {
                $assignment->writing_date = $writing_date;
            }

            // the week is rather an assignment number
            // -> patient may be in week 5 but 7 weeks passed since the first
            //    assignment
            // TODO: adapt seeder - generate appropriate test data
            $assignment->week = $week;

            // 60% chance: the patient completed the assignment
            // (the patient sent in a final text)
            $saved = (rand(0, 10) <= 6) && $is_past_assignment;

            // save assignment to DB
            // don't save timestamps (for testing) and
            // mark entry - assignment is generated by seeder
            Helper::set_developer_attributes($assignment, true);

            switch ($assignment->type) {
                case AssignmentType::SITUATION_SURVEY:
                    $situation_count = $saved && $is_past_assignment ? rand(1,3) : 0;

                    for ($count = 1; $count <= $situation_count; $count++) {
                        $this->add_situation($assignment);
                    }
                    break;
                case AssignmentType::TASK:
                    // choose random template
                    $template = TaskTemplate::all()->random();
                    // 75% chance: the templates text wasn't modified
                    $assignment->problem = (rand(0, 3) === 0) ? $this->faker->realText() : $template->problem;

                    // answer may be empty if nothing was sent in
                    if (!$saved && (rand(0, 10) <= 2)) {
                        $assignment->answer = "";
                    }

                    // add link to the template
                    $assignment->task_template()->associate($template);
                    break;
            }

            if (!$saved && $is_past_assignment) {
                $date_of_reminder = $assignment->writing_date
                    ->addDays(config('gsa.reminder_period_in_days'));

                if ($assignment->answer === "") {
                    $assignment->dirty = false;
                } else {
                    $assignment->dirty = true;
                }

                if ($date_of_reminder->isPast()) {
                    $assignment->date_of_reminder = $date_of_reminder;
                } else {
                    $assignment->date_of_reminder =
                        $this->faker->dateTimeBetween($assignment->writing_date, 'now');
                }
            } else {
                $assignment->dirty = false;
            }

            $patient->assignments()->save($assignment);

            $saved = $saved && $assignment->partially_answered;

            if ($saved && ($week < $assignment_count - 1)) {
                // generate comment if patient has finished assignment
                $has_comment = rand(0,10) <= 8;

                if ($has_comment) {
                    $this->add_comment($assignment);
                    $has_reply = rand(0,10) <= 6;

                    if ($has_reply) {
                        $this->add_comment_reply($assignment->comment);
                    }

                    $this->add_survey_results($assignment);
                }

                $this->add_statistics($patient);
            }
        }
    }

    /**
     * Add a situation and associate it with the situation survey (the first
     * assignment). Some properties are randomly set.
     *
     * @param SituationSurvey $survey
     *          the situation survey
     */
    protected function add_situation(&$survey) {
        // answers generated in model factory
        $situation = factory(Situation::class)->make();
        Helper::set_developer_attributes($situation, true);
        // add situation
        $survey->situations()->save($situation);
    }

    /**
     * Add a comment and associate it with the assignment and the therapist.
     * Some properties are randomly set.
     *
     * @param Assignment $assignment
     *          the assignment
     */
    protected function add_comment(Assignment &$assignment) {
        $comment = factory(Comment::class)->make();
        Helper::set_developer_attributes($comment);

        // answer is commented within 48 hours
        // (this may result in comments late at night ^^)
        $comment->date = $assignment->writing_date->copy()->addHours(rand(0,48));

        // comment should ly in the past
        if ($comment->date->isFuture()) {
            $comment->date = $this->faker->dateTimeBetween($assignment->writing_date, 'now');
        }

        $comment->save();

        // associate the comment with the therapist and the assignment
        $assignment->comment()->save($comment);
        $assignment->patient->therapist->comments()->save($comment);
    }

    /**
     * Add a comment reply and associate it with the comment. Some properties
     * are randomly set.
     *
     * @param Comment $comment
     *          the comment
     */
    protected function add_comment_reply(Comment &$comment) {
        $reply = factory(CommentReply::class)->make();
        Helper::set_developer_attributes($reply, true);

        $comment->comment_reply()->save($reply);
    }

    /**
     * Add survey results to the given assignment. Some properties are
     * randomly set.
     *
     * @param Assignment $assignment
     *          the assignment
     */
    protected function add_survey_results(Assignment &$assignment) {
        $survey = factory(Survey::class)->make();
        Helper::set_developer_attributes($survey, true);

        $assignment->survey()->save($survey);
        $survey->assignment()->associate($assignment);
    }

    /**
     * Add some statistics (like date of last activity). Dates are randomly
     * generated (but they are coherent).
     *
     * @param Patient $patient
     *          the patient
     */
    protected function add_statistics(Patient &$patient) {
        // the login date needs to be coherent
        // -> assume that the patient has viewed the current assignment
        // (if one exists)
        $latest_assignment = $patient->latest_assignment_for_date(Date::now());

        if ($latest_assignment) {
            if ($latest_assignment->comment) {
                $reference_date = $latest_assignment->comment->date;
            } else {
                $reference_date = $latest_assignment->writing_date;
            }
        }  else if ($patient->date_from_clinics) {
            $reference_date = $patient->date_from_clinics;
        } else {
            $reference_date = $patient->registration_date;
        }

        $patient->last_login = $this->faker->dateTimeBetween($reference_date, 'now');
        // if patient didn't log out -> was still logged in and active (remember me)
        $patient->last_activity = $this->faker->dateTimeBetween($patient->last_login, 'now');
    }

    /**
     * Create a bunch of past assignments and create a current assignment matching the
     * given status.
     *
     * @param Patient $patient
     *              the patient
     * @param $patient_status
     *              the status (for the current assignment)
     */
    protected function fill_in_assignments(Patient &$patient, $patient_status) {
        switch ($patient_status) {
            case PatientStatus::REGISTERED:
            case PatientStatus::DATE_OF_DEPARTURE_SET:
                $this->create_empty_assignments($patient);
                break;
            case PatientStatus::INTERVENTION_ENDED:
                $this->create_fixed_number_of_past_assignments($patient, 12, 1);
                break;
            default:
                $this->create_random_number_of_past_assignments($patient, 11);
        }

        $patient->load('assignments');

        $current_assignment = $patient->current_assignment();

        switch ($patient_status) {
            case PatientStatus::REGISTERED:
                break;
            case PatientStatus::DATE_OF_DEPARTURE_SET:
                $patient->date_from_clinics = Date::instance($this->faker->dateTimeBetween($patient->registration_date, 'now'));
                break;
            case PatientStatus::PATIENT_GOT_ASSIGNMENT:
                $current_assignment->dirty = false;
                $current_assignment->date_of_reminder = null;
                $this->fill_in_answers($current_assignment, true);
                break;
            case PatientStatus::PATIENT_EDITED_ASSIGNMENT:
                $current_assignment->dirty = true;
                $current_assignment->date_of_reminder = null;
                $this->fill_in_answers($current_assignment, false);
                break;
            case PatientStatus::SYSTEM_SHOULD_REMIND_OF_ASSIGNMENT:
                $current_assignment->dirty = true;
                $current_assignment->date_of_reminder =
                    $this->faker->dateTimeBetween($current_assignment->writing_date, 'now');
                $this->fill_in_answers($current_assignment, false);
                break;
            case PatientStatus::PATIENT_FINISHED_ASSIGNMENT:
                $current_assignment->dirty = false;
                $current_assignment->date_of_reminder = null;
                $this->fill_in_answers($current_assignment, false);
                break;
            case PatientStatus::THERAPIST_COMMENTED_ASSIGNMENT:
                $current_assignment->dirty = false;
                $current_assignment->date_of_reminder = null;
                $this->fill_in_answers($current_assignment, false);
                $this->add_comment($current_assignment);
                break;
            case PatientStatus::PATIENT_RATED_COMMENT:
                $current_assignment->dirty = false;
                $current_assignment->date_of_reminder = null;
                $this->fill_in_answers($current_assignment, false);
                $this->add_comment($current_assignment);
                $this->add_comment_reply($current_assignment->comment);
                break;
            case PatientStatus::COLLABORATION_ENDED:
                $patient->intervention_ended_on = Date::now();
                break;
        }

        $current_assignment === null ?: $current_assignment->push();
        $patient->push();
    }

}
