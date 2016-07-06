<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseTextLengths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE assignments MODIFY COLUMN problem TEXT');
        DB::statement('ALTER TABLE assignments MODIFY COLUMN answer TEXT');

        DB::statement('ALTER TABLE comments MODIFY COLUMN text TEXT');

        DB::statement('ALTER TABLE task_templates MODIFY COLUMN problem TEXT');

        DB::statement('ALTER TABLE situations MODIFY COLUMN description TEXT');
        DB::statement('ALTER TABLE situations MODIFY COLUMN expectation TEXT');
        DB::statement('ALTER TABLE situations MODIFY COLUMN my_reaction TEXT');
        DB::statement('ALTER TABLE situations MODIFY COLUMN their_reaction TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE assignments MODIFY COLUMN problem VARCHAR(255)');
        DB::statement('ALTER TABLE assignments MODIFY COLUMN answer VARCHAR(255)');

        DB::statement('ALTER TABLE comments MODIFY COLUMN text VARCHAR(255)');

        DB::statement('ALTER TABLE task_templates MODIFY COLUMN problem VARCHAR(255)');

        DB::statement('ALTER TABLE situations MODIFY COLUMN description VARCHAR(255)');
        DB::statement('ALTER TABLE situations MODIFY COLUMN expectation VARCHAR(255)');
        DB::statement('ALTER TABLE situations MODIFY COLUMN my_reaction VARCHAR(255)');
        DB::statement('ALTER TABLE situations MODIFY COLUMN their_reaction VARCHAR(255)');
    }
}
