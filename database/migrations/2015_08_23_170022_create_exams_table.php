<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exams', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->timestamp('date_start');
			$table->timestamp('date_end');

			$table->string('description')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')
				->references('id')
				->on('examTypes')
				->onDelete('CASCADE');

			$table->integer('create_uid')->unsigned();
			$table->foreign('create_uid')
				->references('id')
				->on('users')
				->onDelete('NO ACTION');

			$table->integer('write_uid')->unsigned()->nullable();
			$table->foreign('write_uid')
				->references('id')
				->on('users')
				->onDelete('NO ACTION');

			$table->integer('academic_year_id')->unsigned()->nullable();
			$table->foreign('academic_year_id')
				->references('id')
				->on('academicYears')
				->onDelete('NO ACTION');

			// Parameters
			$table->integer('number_room_controller')->default(2);
			$table->integer('number_floor_controller')->default(10);
            $table->integer('math_score_quote')->nullable();
            $table->integer('phys_chem_score_quote')->nullable();
            $table->integer('logic_score_quote')->nullable();

		});

        Schema::create('exam_room', function(Blueprint $table)
        {
            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('room_id')->unsigned()->index();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            $table->string('roomcode')->nullable();
        });

        Schema::create('employee_exam', function(Blueprint $table)
        {
            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->integer('employee_id')->unsigned()->index();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('role')->nullable();
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exam_room');
		Schema::drop('employee_exam');
		Schema::drop('exams');
	}

}
