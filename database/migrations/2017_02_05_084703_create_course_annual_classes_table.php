<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseAnnualClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_annual_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('group')->nullable();

            $table->integer('degree_id')->unsigned()->index()->nullable();
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('NO ACTION');

            $table->integer('grade_id')->unsigned()->index()->nullable();
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('NO ACTION');

            $table->integer('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('NO ACTION');

            $table->integer('course_annual_id')->unsigned()->index()->nullable();
            $table->foreign('course_annual_id')
                ->references('id')
                ->on('course_annuals')
                ->onDelete('NO ACTION');


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
        Schema::drop('course_annual_classes');
    }
}
