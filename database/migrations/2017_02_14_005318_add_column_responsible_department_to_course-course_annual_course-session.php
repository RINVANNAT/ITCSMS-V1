<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnResponsibleDepartmentToCourseCourseAnnualCourseSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('responsible_department_id')->unsigned()->index()->nullable();
            $table->foreign('responsible_department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('CASCADE');
        });
        Schema::table('course_annuals', function (Blueprint $table) {
            $table->integer('responsible_department_id')->unsigned()->index()->nullable();
            $table->foreign('responsible_department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('CASCADE');
        });
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->integer('responsible_department_id')->unsigned()->index()->nullable();
            $table->foreign('responsible_department_id')
                ->references('id')
                ->on('departments')
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
        //
    }
}
