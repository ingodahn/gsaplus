<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * for testing - TODO: remove when project is finished
 */
class AddRandomAttribute extends Migration
{

    const TABLE_NAMES = ['users',
        'assignments',
        'assignment_templates',
        'comments',
        'comment_replies',
        'phq4',
        'wai',
        'surveys'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::TABLE_NAMES as $table_name) {
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
        foreach (self::TABLE_NAMES as $table_name) {
            Schema::table($table_name, function($table) {
                $table->dropColumn('is_random');
            });
        }
    }
}
