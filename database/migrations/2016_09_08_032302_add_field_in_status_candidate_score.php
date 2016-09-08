<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInStatusCandidateScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statusCandidateScores', function (Blueprint $table) {

            $table->integer('entrance_exam_course_id')->unsigned()->nullable();
            $table->foreign('entrance_exam_course_id')
                ->references('id')
                ->on("entranceExamCourses")
                ->onDelete('CASCADE');

            $table->integer('exam_id')->unsigned()->nullable();
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
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
        Schema::table('statusCandiateScores', function (Blueprint $table) {

        });
    }
}
