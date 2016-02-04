<?php

use Illuminate\Database\Seeder;

use App\Code;

class CodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = factory(App\Code::class, 100)
            ->create();

        foreach (range('A','Z') as $letter) {
            $code = new Code;
            $code->value = str_repeat($letter, 3);
            $code->save();
        }
    }
}
