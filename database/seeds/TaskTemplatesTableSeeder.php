<?php

use Illuminate\Database\Seeder;

use App\TaskTemplate;

use App\Helper;

class TaskTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskTemplates = factory(TaskTemplate::class, 4)
            ->make()
            ->each(function (TaskTemplate $t) {
                Helper::set_developer_attributes($t, true);
            });
    }
}
