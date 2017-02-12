<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseSessionIdInCourseAnnualClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annual_classes', function (Blueprint $table) {


            $table->integer('course_session_id')->unsigned()->index()->nullable();
            $table->foreign('course_session_id')
                ->references('id')
                ->on('course_sessions')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_annual_classes', function (Blueprint $table) {
            //
        });
    }
}
