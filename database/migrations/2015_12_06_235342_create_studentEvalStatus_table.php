<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEvalStatusTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('studentEvalStatuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name');
			$table->timestamps();
		});

		Schema::create('student_annual_student_eval_status', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('student_eval_status_id');
			$table->unsignedInteger('student_annual_id');

		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('studentEvalStatuses');
		Schema::drop('student_annual_student_eval_status');
	}

}
