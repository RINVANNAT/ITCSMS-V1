<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_tp')->default(0);
            $table->integer('time_td')->default(0);
            $table->integer('time_course')->default(0);
            $table->integer('academic_year_id')->unsigned();
            $table->integer('course_program_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->integer('created_uid')->unsigned();
            $table->integer('write_uid')->unsigned()->nullable();


            $table->foreign('course_program_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('cascade');

            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');

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
        Schema::drop('slots');
    }
}
