<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSemesterIdToTableRedoubleStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redouble_student', function (Blueprint $table) {
            $table->integer('semester_id')->nullable()->default(2);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redouble_student', function (Blueprint $table) {
            $table->dropColumn('semester_id');
        });
    }
}
