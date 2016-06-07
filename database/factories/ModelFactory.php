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
    $userName = $faker->userName;

    return [
        'name' => $userName,
        'email' => $userName.'@mailinator.com',
        'password' => bcrypt('password'), // str_random(10)
    ];
});


$factory->define(App\Patient::class, function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, [
        'code' => strtoupper(str_random(4)).'-'
                    .strtoupper(str_random(4)).'-'
                    .strtoupper(str_random(4)).'-'
                    .strtoupper(str_random(4)),
        'assignment_day' => $faker->numberBetween($min = 0, $max = 4),
        'assignment_day_changes_left' => $faker->numberBetween($min = 0, $max = 3)
    ]);
});

$factory->define(App\Therapist::class, function () use ($factory) {
    return $factory->raw(App\User::class);
});

$factory->define(App\Admin::class, function () use ($factory) {
    return $factory->raw(App\User::class);
});

$factory->define(App\Assignment::class, function () {
    return [
        'dirty' => rand(0,1),
        'week' => rand(1,12)
    ];
});

$factory->define(App\SituationSurvey::class, function () use ($factory) {
    return $factory->raw(App\Assignment::class);
});

$factory->define(App\Situation::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->realText(),
        'expectation' => $faker->realText(),
        'my_reaction' => $faker->realText(),
        'their_reaction' => $faker->realText()
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) use ($factory) {
    $assignment = $factory->raw(App\Assignment::class);

    return array_merge($assignment, [
        'problem' => $faker->realText(),
        'answer'  => $faker->realText()
    ]);
});

$factory->define(App\TaskTemplate::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(20),
        'problem' => $faker->text()
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

$factory->define(App\Survey::class, function () {
    return [
        'wai' => rand(0,10),
        'health' => rand(0,10)
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
        'value' => 'AAAA-AAAA-AAAA-AAAA'
    ];
});

$factory->define(App\TestSetting::class, function() {
   return [
       'test_date' => null
   ];
});