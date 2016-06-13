<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourseExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_annual_exam', function(Blueprint $table)
        {
            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('course_annual_id')->unsigned()->index();
            $table->foreign('course_annual_id')->references('id')->on('courseAnnuals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_annual_exam');
    }
}
