<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentDegreeGradeOptionToCourseAnnual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->nullable()->index();
            $table->integer('degree_id')->unsigned()->nullable()->index();
            $table->integer('grade_id')->unsigned()->nullable()->index();
            $table->integer('department_option_id')->unsigned()->nullable()->index();

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
            $table->foreign('department_option_id')
                ->references('id')
                ->on('departmentOptions')
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
    }
}
