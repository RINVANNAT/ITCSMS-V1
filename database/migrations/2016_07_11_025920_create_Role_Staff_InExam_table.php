<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleStaffInExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roleStaffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();  

        });

        Schema::create('role_temporary_staff_exams', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('role_staff_id')->unsigned()->index();
            $table->foreign('role_staff_id')->references('id')->on('roleStaffs')->onDelete('cascade');

            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('temp_employee_id')->unsigned()->index();
            $table->foreign('temp_employee_id')->references('id')->on('tempEmployees')->onDelete('cascade');
        });

        Schema::create('role_permanent_staff_exams', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('role_staff_id')->unsigned()->index();
            $table->foreign('role_staff_id')->references('id')->on('roleStaffs')->onDelete('cascade');

            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('employee_id')->unsigned()->index();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roleStaffs');
        Schema::drop('role_temporary_staff_exams');
        Schema::drop('role_permanent_staff_exams');
    }
}
