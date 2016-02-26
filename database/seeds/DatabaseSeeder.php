<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

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

        $admin = factory(App\Admin::class, 4)
            ->make()
            ->each(function(App\Admin $a) {
                $a->timestamps = false;
                $a->is_random = true;
                $a->save();
            });

        $therapists = factory(App\Therapist::class, 4)
            ->make()
            ->each(function (App\Therapist $t) {
                $t->timestamps = false;
                $t->is_random = true;
                $t->save();
            });

        $assignmentTemplates = factory(App\AssignmentTemplate::class, 4)
            ->make()
            ->each(function (App\AssignmentTemplate $t) {
                $t->timestamps = false;
                $t->is_random = true;
                $t->save();
            });;

        $patients = factory(App\Patient::class, 20)
            ->make()
            ->each(function (App\Patient $p) use ($faker) {
                $p->timestamps = false;
                $p->is_random = true;
                $p->personal_information = $faker->realText();
                $p->notes_of_therapist = $faker->realText();
                $p->save();
            });;

        foreach ($patients as $patient) {
            // every patient has a random number of assignments
            $assignment_count = rand(1,10);
            // choose random therapist
            $therapist = App\Therapist::all()->random();
            $therapist->patients()->save($patient);

            // the registration happened before the first assignment
            // add 1-3 weeks between the registration and the first assignment
            $patient->registration_date = Carbon::now()->startOfWeek()
                    ->subWeeks($assignment_count + rand(1,3));

            // create a bunch of successive assignments
            for ($count = 1; $count <= $assignment_count; $count++) {
                // the assignment should happen in the past /
                // the assignment should happen during work hours
                $assignment_date = Carbon::now()->startOfWeek()->addHours(rand(8,18));

                // use the chosen weekday
                $assignment_date->addDays($patient->assignment_day);
                // assignments should be successive
                $assignment_date->subWeeks($assignment_count - $count + 1);

                // create the actual assignment
                $assignment = factory(App\Assignment::class)->make();
                // don't save timestamps (for testing)
                $assignment->timestamps = false;
                // mark entry - assignment is generated by seeder
                $assignment->is_random = true;

                $assignment->assigned_on = $assignment_date;

                // 60% chance: the patient completed the assignment
                //(the patient sent in a final text)
                $saved = rand(0,10) <= 6;
                $saved ? $assignment->state = true : $assignment->state = false;

                // save assignment to DB
                $assignment->save();

                // choose random template
                App\AssignmentTemplate::all()->random()->assignments()->save($assignment);
                $patient->assignments()->save($assignment);

                // generate response if patient has finished assignment
                if ($saved) {
                    $response = factory(App\Response::class)->make();
                    $response->timestamps = false;
                    $response->is_random = true;

                    // response is created within 48 hours
                    // (this may result in responses late at night ^^)
                    $response->date = $assignment_date->copy()->addHours(rand(0,48) - $assignment_date->hour);

                    $response->save();

                    // associate the response with the therapist
                    // and the assignment
                    $assignment->response()->save($response);
                    $therapist->responses()->save($response);
                }
            }

            // date of departure has to be between the registration date und the first assignment
            $patient->date_from_clinics =
                $faker->dateTimeBetween($patient->registration_date,
                    $patient->assignments->sortBy('assigned_on')->first()->assigned_on);
                // the login date needs to be coherent
            // -> assume that the patient has viewed the last assignment
            $patient->last_login = $faker->dateTimeBetween(
                $patient->assignments()->get()->sortBy('assigned_on')->last()->assigned_on, 'now');
            // assume user didn't logout
            $patient->last_activity = $faker->dateTimeBetween($patient->last_login, 'now');
            // save the registration date, login date
            $patient->save();
        }
    }
}
