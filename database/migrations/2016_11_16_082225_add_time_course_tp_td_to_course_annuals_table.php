<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeCourseTpTdToCourseAnnualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            //
            $table->integer('time_tp')->nullable();
            $table->integer('time_td')->nullable();
            $table->integer('time_course')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            //
        });
    }
}
