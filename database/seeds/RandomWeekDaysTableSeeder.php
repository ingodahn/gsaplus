<?php

use Illuminate\Database\Seeder;
use Jenssegers\Date\Date;
use App\WeekDay;
use App\Helper;

class RandomWeekDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = Helper::generate_date_map();

        $faker = Faker\Factory::create();

        // generate an entry for every day and set a random number of free time slots
        foreach ($days as $day => $number) {
            $entry = new WeekDay();

            $entry->number = $number;
            $entry->name = $day;
            $entry->free_time_slots = $faker->numberBetween($min = 0, $max = 10);

            $entry->save();
        }
    }
}
