<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('academic_year_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('degree_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->integer('option_id')->nullable();
            $table->integer('semester_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->integer('group_id')->nullable();

            $table->boolean('completed')->default(false);

            $table->integer('created_uid');
            $table->integer('updated_uid');

            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('cascade');

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');

            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('cascade');

            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('cascade');

            $table->foreign('option_id')
                ->references('id')
                ->on('departmentOptions')
                ->onDelete('cascade');

            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');

            $table->foreign('week_id')
                ->references('id')
                ->on('weeks')
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
        Schema::drop('timetables');
    }
}
