<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateEntranceExamScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidateEntranceExamScores', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('score_c')->nullable();
            $table->integer('score_w')->nullable();
            $table->integer('score_na')->nullable();
            $table->integer('sequence')->nullable();
            $table->boolean('is_firstTime')->default(false);
            $table->boolean('is_secondTime')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->integer('candidate_id')->unsigned()->nullable();
            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('CASCADE');

            $table->integer('entrance_exam_course_id')->unsigned()->nullable();
            $table->foreign('entrance_exam_course_id')
                ->references('id')
                ->on('entranceExamCourses')
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
        Schema::drop('candidateEntranceExamScores');
    }
}
