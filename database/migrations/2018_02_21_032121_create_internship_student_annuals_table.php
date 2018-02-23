<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternshipStudentAnnualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internship_student_annuals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('internship_id')->unsigned();
            $table->integer('student_annual_id')->unsigned();
            $table->foreign('internship_id')->references('id')->on('internships')->onDelete('CASCADE');
            $table->foreign('student_annual_id')->references('id')->on('studentAnnuals')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('internship_student_annuals');
    }
}
