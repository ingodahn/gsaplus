<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $registrationDate = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now');
    $loginDate = $faker->dateTimeBetween($registrationDate, $endDate = 'now');

    return [
        'name' => $faker->userName,
        'email' => $faker->email,
        'password' => bcrypt('password'), // str_random(10)
        // 'remember_token' => str_random(10),
        'last_login' => $loginDate,
        'registration_date' => $registrationDate,
        'created_at' => $registrationDate,
        'updated_at' => $loginDate
    ];
});


$factory->define(App\Patient::class, function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, [
        'code' => strtoupper(str_random(6)),
        'assignment_day' => $faker->numberBetween($min = 0, $max = 4),
        'assignment_day_changes_left' => $faker->numberBetween($min = 0, $max = 3)

        // patient status should be determined - not cached
        // 'patient_status' => 'P130'
    ]);
});

$factory->define(App\Therapist::class, function (Faker\Generator $faker) use ($factory) {
    return $factory->raw(App\User::class);
});

$factory->define(App\Admin::class, function (Faker\Generator $faker) use ($factory) {
    return $factory->raw(App\User::class);
});

$factory->define(App\Assignment::class, function (Faker\Generator $faker) {
    return [
        'assigned_on' => $faker->dateTime($max = 'now'),
        'patient_text' => $faker->text()
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->dateTime($max = 'now'),
        'text' => $faker->text()
    ];
});

$factory->define(App\CommentReply::class, function (Faker\Generator $faker) {
    return [
        'helpful' => rand(0,4),
        'satisfied' => rand(0,4)
    ];
});

$factory->define(App\AssignmentTemplate::class, function (Faker\Generator $faker) {
    return [
        'title' => str_random(20),
        'text' => $faker->text()
    ];
});

$factory->define(App\WeekDay::class, function (Faker\Generator $faker) {
    $date = Date::createFromTimeStamp($faker->dateTime()->getTimestamp());

    return [
        'number' => $date->dayOfWeek,
        'name' => $date->format('l'),
        'free_time_slots' => $faker->numberBetween($min = 0, $max = 10)
    ];
});

$factory->define(App\Code::class, function () {
   return [
        'value' => strtoupper(str_random(3))
    ];
});