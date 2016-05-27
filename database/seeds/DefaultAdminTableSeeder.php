<?php

use App\Admin;

use Illuminate\Database\Seeder;

class DefaultAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin;

        $admin->name = getenv('ADMIN_NAME');
        $admin->password = bcrypt(getenv('ADMIN_PASS'));
        $admin->email = getenv('MAIL_ADMIN_ADDRESS');

        $admin->save();
    }
}
