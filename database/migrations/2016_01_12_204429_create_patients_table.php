<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code');
            $table->tinyInteger('assignment_day');
            $table->tinyInteger('assignment_day_changes_left');
            $table->enum('patient_status', ['P010', 'P020', 'P025', 'P030', 'P040', 'P045', 'P050', 'P060',
                'P065', 'P070', 'P075', 'P080', 'P090', 'P095', 'P100', 'P110', 'P115', 'P120', 'P130', 'P140']);

            $table->integer('therapist_id')->unsigned();
            $table->integer('assignment_id')->unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patients');
    }
}
