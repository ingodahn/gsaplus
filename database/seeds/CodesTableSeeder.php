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
        $codes = factory(App\Code::class, 17)
            ->create();

        foreach (['AAA', 'BBB', 'CCC'] as $id) {
            $code = new Code;
            $code->id = $id;
            $code->save();
        }
    }
}
