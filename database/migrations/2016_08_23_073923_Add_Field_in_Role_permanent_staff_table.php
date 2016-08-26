<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInRolePermanentStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {

            $table->integer('room_id')->unsigned()->nullable();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
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
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {
            $table->dropColumn('room_id');
            $table->dropColumn('entrance_exam_course_id');
        });
    }
}
