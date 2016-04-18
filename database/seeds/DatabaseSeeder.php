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
        $this->call(CodesTableSeeder::class);
        $this->call(RandomWeekDaysTableSeeder::class);

        $this->call(AdminsTableSeeder::class);
        $this->call(TherapistsTableSeeder::class);
        $this->call(TaskTemplatesTableSeeder::class);

        $this->call(RandomPatientsTableSeeder::class);
    }

}
