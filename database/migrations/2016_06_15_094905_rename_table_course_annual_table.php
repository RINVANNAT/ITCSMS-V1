<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableCourseAnnualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('course_annuals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('semester_id');
            $table->timestamps();
            $table->boolean('active')->default(true);

            $table->integer('academic_year_id')->unsigned()->index();
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('NO ACTION');

            $table->integer('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('NO ACTION');

            $table->integer('create_uid')->unsigned()->index()->nullable();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
            $table->integer('department_id')->unsigned();
            $table->integer('degree_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->integer('course_id')->unsigned();
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

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('course_annuals');
    }
}
