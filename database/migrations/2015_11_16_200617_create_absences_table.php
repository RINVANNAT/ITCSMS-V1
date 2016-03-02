<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsencesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('degree_id');
			$table->unsignedInteger('grade_id');
			$table->unsignedInteger('department_id');
			$table->unsignedInteger('academic_year_id');
			$table->unsignedInteger('semester_id');
			$table->unsignedInteger('course_annual_id');
			$table->unsignedInteger('student_annual_id');
			$table->dateTime('absence_on');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('absences');
	}

}
