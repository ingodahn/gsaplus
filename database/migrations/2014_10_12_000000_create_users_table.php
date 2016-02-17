<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email');
            $table->string('password', 60);
            $table->dateTime('last_login');
            $table->dateTime('registration_date');

            $table->string('code');
            $table->tinyInteger('assignment_day');
            $table->tinyInteger('assignment_day_changes_left');

            // patient status should be determined - not cached
            // $table->enum('patient_status', ['P010', 'P020', 'P025', 'P030', 'P040', 'P045', 'P050', 'P060',
            //    'P065', 'P070', 'P075', 'P080', 'P090', 'P095', 'P100', 'P110', 'P115', 'P120', 'P130', 'P140']);

            $table->integer('therapist_id')->unsigned();

            $table->enum('type', ['patient', 'therapist', 'admin']);

            $table->rememberToken();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
