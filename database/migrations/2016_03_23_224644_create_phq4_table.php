<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhq4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phq4', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('depressed')->unsigned();
            $table->tinyInteger('interested')->unsigned();
            $table->tinyInteger('nervous')->unsigned();
            $table->tinyInteger('troubled')->unsigned();

            $table->integer('survey_id')->unsigned();

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
        Schema::drop('phq4');
    }
}
