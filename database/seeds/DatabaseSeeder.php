<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WeekDaysTableSeeder::class);

        $admin = factory(App\Admin::class, 4)
            ->create()
            ->each(function (App\Admin $a) {
                $a->user()->save(factory(App\User::class)->make());
            });

        $therapists = factory(App\Therapist::class, 4)
            ->create()
            ->each(function (App\Therapist $t) {
                $t->user()->save(factory(App\User::class)->make());
            });

        $patients = factory(App\Patient::class, 20)
            ->create()
            ->each(function (App\Patient $p) {
                $p->user()->save(factory(App\User::class)->make());
                App\Therapist::all()->random()->patients()->save($p);
            });

        $assignmentTemplates = factory(App\AssignmentTemplate::class, 4)
            ->create();

        $assignments = factory(App\Assignment::class, 60)
            ->create()
            ->each(function (App\Assignment $a) {
                App\AssignmentTemplate::all()->random()->assignments()->save($a);
                App\Patient::all()->random()->assignments()->save($a);
            });

        foreach ($assignments as $assignment) {
            // 3 chance out of 4, which is 75%
            if (mt_rand(0, 4) !== 0) {
                $response = factory(App\Response::class)->create();
                // if the therapist isn't always the same
                $therapist = App\Therapist::all()->random();

                $assignment->response()->save($response);
                $therapist->responses()->save($response);
            }
        }
    }
}
