<?php

use Illuminate\Database\Seeder;
use App\WeekDay;

class ConstantWeekDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RandomWeekDaysTableSeeder::class);

        foreach (WeekDay::all() as $day) {
            $day->free_time_slots = $day->number + 1;
            $day->save();
        }
    }
}
