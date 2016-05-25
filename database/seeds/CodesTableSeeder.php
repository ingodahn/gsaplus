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

        foreach (range('A','C') as $first_letter) {
            $code_count = 0;

            while ($code_count < 120) {
                $code = $this->generateCode($first_letter);

                if (in_array($code, $codes)) {
                    continue;
                } else {
                    $codes[] = $code;
                    $code_count++;
                }
            }
        }

        foreach ($codes as $code) {
            $entry = new Code;
            $entry->value = $code;
            $entry->save();
        }
    }

    protected function generateCode($first_letter = null) {
        $code = '';

        for ($i=0; $i<4; $i++) {
            if ($first_letter && $i === 0) {
                $code.=$this->generateCodeFragment($first_letter);
            } else {
                $code.=$this->generateCodeFragment();
            }

            $code = ($i === 3 ? $code : $code.'-');
        }

        return $code;
    }

    protected function generateCodeFragment($first_letter = null) {
        $fragment = '';

        for ($i=0; $i<4; $i++) {
            if ($first_letter && $i === 0) {
                $fragment.=$first_letter;
            } else {
                $fragment.=range('A','Z')[rand(0,25)];
            }
        }

        return $fragment;
    }

}
