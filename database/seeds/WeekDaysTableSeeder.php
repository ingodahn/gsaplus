<?php

use Illuminate\Database\Seeder;
use Jenssegers\Date\Date;
use App\WeekDay;

class WeekDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $date = new Date('next Sunday');

        $days = array();

        // generate an array containing the days names
        for ($i = 0; $i < 7; $i++) {
            $days[] = $date->format('l');
            $date = $date->add('1 day');
        }

        // generate an entry for every day and set a random number of free time slots
        foreach (range(0,6) as $day) {
            $entry = new WeekDay();

            $entry->number = $day;
            $entry->name = $days[$day];
            $entry->free_time_slots = $faker->numberBetween($min = 0, $max = 10);

            $entry->save();
        }
    }
}
