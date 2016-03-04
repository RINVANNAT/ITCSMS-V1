<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scholarships', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_kh')->nullable();
			$table->string('name_en')->nullable();
			$table->string('name_fr')->nullable();
			$table->string('code');
            $table->boolean('isDroppedUponFail')->default(true);
            $table->enum('duration',['1 year','2 years','3 years','4 years','5 years','Full'])->nullable();
            $table->string('founder')->nullable();
            $table->date('start')->nullable();
            $table->date('stop')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->boolean('active')->default(true);

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
		});

        Schema::create('scholarship_student_annual', function(Blueprint $table)
        {
            $table->integer('scholarship_id')->unsigned()->index();
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('cascade');

            $table->integer('student_annual_id')->unsigned()->index();
            $table->foreign('student_annual_id')->references('id')->on('studentAnnuals')->onDelete('cascade');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('scholarship_student_annual');
		Schema::drop('scholarships');
	}

}
