<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAnnualsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('studentAnnuals', function(Blueprint $table)
		{
			$table->increments('id');
            $table->timestamps();
            $table->string('group')->nullable();
            $table->boolean('active')->default(true);

            $table->integer('promotion_id')->unsigned()->index();
            $table->integer('department_id')->unsigned()->index();
            $table->integer('degree_id')->unsigned()->index();
            $table->integer('grade_id')->unsigned()->index();
            $table->integer('academic_year_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->integer('history_id')->unsigned()->index()->nullable();
            $table->integer('create_uid')->unsigned()->index();
            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->integer('department_option_id')->unsigned()->index()->nullable();
            $table->integer('payslip_client_id')->unsigned()->nullable();

            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->onDelete('cascade');
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
            $table->foreign('department_option_id')
                ->references('id')
                ->on('departmentOptions')
                ->onDelete('cascade');
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('cascade');
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('cascade');
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('cascade');
            $table->foreign('history_id')
                ->references('id')
                ->on('histories')
                ->onDelete('NO ACTION');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
                ->onDelete('cascade');
		});

        Schema::create('exam_studentAnnual', function(Blueprint $table)
        {
            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('studentAnnual_id')->unsigned()->index();
            $table->foreign('studentAnnual_id')->references('id')->on('studentAnnuals')->onDelete('cascade');
        });



	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('exam_studentAnnual');
		Schema::drop('studentAnnuals');
	}

}
