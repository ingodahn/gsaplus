<?php

use App\Models\AssignmentType;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('week');
            $table->date('writing_date')->nullable();
            $table->boolean('dirty');

            // attributes for tasks
            $table->string('problem');
            $table->string('answer');

            $table->dateTime('date_of_reminder')->nullable();

            // attributes for situations
            // none (link is hasOne in model -> id is stored in situations table)

            $table->enum('type', [AssignmentType::SITUATION_SURVEY, AssignmentType::TASK]);

            $table->integer('task_template_id')->unsigned();
            $table->integer('patient_id')->unsigned();

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
        Schema::drop('assignments');
    }
}
