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
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'last_login' => $loginDate,
        'registration_date' => $registrationDate,
        'created_at' => $registrationDate,
        'updated_at' => $loginDate
    ];
});

$factory->define(App\Patient::class, function (Faker\Generator $faker) {
    return [
        'code' => str_random(10),
        'assignment_day' => $faker->numberBetween($min = 0, $max = 6),
        'assignment_day_changes_left' => $faker->numberBetween($min = 0, $max = 3)
    ];
});

$factory->define(App\Therapist::class, function (Faker\Generator $faker) {
    return [

    ];
});

$factory->define(App\Admin::class, function (Faker\Generator $faker) {
    return [

    ];
});

$factory->define(App\Assignment::class, function (Faker\Generator $faker) {
    return [
        'assigned_on' => $faker->dateTime($max = 'now'),
        'patient_text' => $faker->text()
    ];
});

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->dateTime($max = 'now'),
        'text' => $faker->text()
    ];
});

$factory->define(App\AssignmentTemplate::class, function (Faker\Generator $faker) {
    return [
        'title' => str_random(20),
        'text' => $faker->text()
    ];
});
