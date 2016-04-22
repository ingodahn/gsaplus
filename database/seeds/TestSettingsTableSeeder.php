<?php

use App\TestSetting;

use Illuminate\Database\Seeder;

class TestSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestSetting::class)->create();
    }
}
