<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->date('test_date')->nullable();

            $table->boolean('first_reminder')->default(false);
            $table->boolean('new_reminder')->default(false);
            $table->boolean('due_reminder')->default(false);
            $table->boolean('calc_next_writing_date')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_settings');
    }
}
