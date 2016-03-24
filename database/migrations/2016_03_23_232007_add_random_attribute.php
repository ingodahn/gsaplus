<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * for testing - TODO: remove when project is finished
 */
class AddRandomAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['users', 'assignments', 'assignment_templates', 'comments', 'comment_replies', 'phq4', 'wai', 'surveys'] as $table_name) {
            Schema::table($table_name, function($table) {
                $table->boolean('is_random');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['users', 'assignments', 'assignment_templates', 'comments', 'comment_replies', 'phq4', 'wai', 'surveys'] as $table_name) {
            Schema::table($table_name, function($table) {
                $table->dropColumn('is_random');
            });
        }
    }
}
