<?php

use Illuminate\Database\Seeder;

use App\Patient;
use App\Admin;
use App\Therapist;

use App\Assignment;
use App\TaskTemplate;

use App\Comment;
use App\CommentReply;

use App\PHQ4;
use App\WAI;
use App\Survey;

use App\SituationSurvey;
use App\Task;
use App\Situation;

use App\Models\AssignmentType;

use Illuminate\Database\Eloquent\Model;

use Jenssegers\Date\Date;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $this->call(CodesTableSeeder::class);
        $this->call(RandomWeekDaysTableSeeder::class);

        $admin = factory(Admin::class, 4)
            ->make()
            ->each(function(Admin $a) {
                $this->set_developer_attributes($a, true);
            });

        $therapists = factory(Therapist::class, 4)
            ->make()
            ->each(function (Therapist $t) {
                $this->set_developer_attributes($t, true);
            });

        $taskTemplates = factory(TaskTemplate::class, 4)
            ->make()
            ->each(function (TaskTemplate $t) {
                $this->set_developer_attributes($t, true);
            });;

        $patients = factory(Patient::class, 20)
            ->make()
            ->each(function (Patient $p) use ($faker) {
                $this->set_developer_attributes($p);

                $p->personal_information = $faker->realText();
                $p->notes_of_therapist = $faker->realText();

                $p->save();
            });;

        foreach ($patients as $patient) {
            // every patient has a random number of past assignments
            $assignment_count = rand(1,12);
            // choose random therapist
            $therapist = Therapist::all()->random();
            $therapist->patients()->save($patient);

            $patient->date_from_clinics = Date::now()
                    ->startOfWeek()
                    ->subWeeks($assignment_count)
                    ->addDays(rand(0,6));

            // the registration happened before the patient left the clinic
            // registration date -> between 1 day and 3 weeks before date of departure
            // TODO: not always the case
            $patient->registration_date = $faker->dateTimeBetween(
                        $patient->date_from_clinics->copy()->subWeeks(3),
                        $patient->date_from_clinics->copy()->subDay());

            // set first assignment day
            $first_assignment_day = $patient->date_from_clinics->copy()->startOfDay()
                ->endOfWeek()->next($patient->assignment_day);

            // create all assignments (all future assignments will be defined)
            for ($week = 1; $week <= 12; $week++) {
                // next writing day is always a week in the future
                // -> not always the case
                // TODO: implement other cases (for testing)
                $writing_date = $first_assignment_day->copy()->addWeeks($week-1);

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
                $saved = (rand(0,10) <= 6) && $is_past_assignment;

                // answer may be empty
                if (!$saved && (rand(0,10) <= 3)) {
                    $assignment->answer = "";
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
                                    $faker->dateTimeBetween($assignment->writing_date, 'now');
                    }
                } else {
                    $assignment->dirty = false;
                }

                // save assignment to DB
                // don't save timestamps (for testing) and
                // mark entry - assignment is generated by seeder
                $this->set_developer_attributes($assignment, true);

                switch ($assignment->type) {
                    case AssignmentType::SITUATION_SURVEY:
                        $situation_count = 3; // rand(1,3);

                        for ($count = 1; $count <= $situation_count; $count++) {
                            // answers generated in model factory
                            $situation = $is_past_assignment && $saved ?
                                            factory(Situation::class)->make() : new Situation;
                            $this->set_developer_attributes($situation, true);
                            // add situation
                            $assignment->situations()->save($situation);
                        }
                        break;
                    case AssignmentType::TASK:
                        // choose random template
                        $template = TaskTemplate::all()->random();
                        // 75% chance: the templates text wasn't modified
                        $assignment->problem = (rand(0,3) === 0) ? $faker->realText() : $template->problem;

                        // add link to the template
                        $assignment->task_template()->associate($template);
                        break;
                }

                $patient->assignments()->save($assignment);

                // generate comment if patient has finished assignment
                if ($saved) {
                    $comment = factory(Comment::class)->make();
                    $this->set_developer_attributes($comment);

                    // answer is commented within 48 hours
                    // (this may result in comments late at night ^^)
                    $comment->date = $assignment->writing_date->copy()->addHours(rand(0,48));

                    // comment should ly in the past
                    if ($week === $assignment_count && $comment->date->isFuture()) {
                        $comment->date = $faker->dateTimeBetween($assignment->writing_date, 'now');
                    }

                    $comment->save();

                    // associate the comment with the therapist
                    // and the assignment
                    $assignment->comment()->save($comment);
                    $therapist->comments()->save($comment);

                    $has_reply = rand(0,10) <= 6;

                    if ($has_reply) {
                        $reply = factory(CommentReply::class)->make();
                        $this->set_developer_attributes($reply, true);

                        $comment->comment_reply()->save($reply);
                    }

                    $survey = factory(Survey::class)->make();
                    $this->set_developer_attributes($survey, true);

                    $phq4 = factory(PHQ4::class)->make();
                    $this->set_developer_attributes($phq4, true);

                    $wai = factory(WAI::class)->make();
                    $this->set_developer_attributes($wai, true);

                    $survey->phq4()->save($phq4);
                    $survey->wai()->save($wai);

                    $assignment->survey()->save($survey);
                    $survey->assignment()->associate($assignment);
                }
            }

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

            $patient->last_login = $faker->dateTimeBetween($reference_date, 'now');
            $patient->last_activity = $faker->dateTimeBetween($patient->last_login, 'now');

            // save
            $this->set_developer_attributes($patient, true);
        }
    }

    /**
     * Tell laravel to ignore timestamps (set to null) and mark entry as random (is_random = true).
     *
     * @param Model $model the target (e.g. an assignment)
     */
    protected function set_developer_attributes(Model &$model, $save = false) {
        $model->is_random = true;
        $model->timestamps = false;

        if ($save) {
            $model->save();
        }
    }

}
