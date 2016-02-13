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
        $codes = [];

        foreach (range(0,100) as $num) {
            $codes[] = strtoupper(str_random(3));
        }

        foreach (range('A','Z') as $letter) {
            $codes[] = str_repeat($letter, 3);
        }

        $unique_codes = array_unique($codes);

        foreach ($unique_codes as $unique_code) {
            $code = new Code;
            $code->value = $unique_code;
            $code->save();
        }
    }
}
