<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('id_card');
			$table->integer('mcs_no')->nullable();
			$table->string('can_id',100)->nullable();
			$table->string('name_latin',255);
			$table->string('name_kh',255);
			$table->timestamp('dob');
			$table->string('photo')->nullable();
			$table->boolean('radie')->nullable();
			$table->string('observation')->nullable();
			$table->string('phone',100)->nullable();
			$table->string('email',100)->nullable();
			$table->timestamp('admission_date')->nullable();
			$table->string('address')->nullable();
			$table->string('address_current');
			$table->string('parent_name')->nullable();
			$table->string('parent_occupation')->nullable();
			$table->string('parent_address')->nullable();
			$table->string('parent_phone',100)->nullable();
			$table->timestamps();
            $table->boolean('active')->default(true);

            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

			$table->integer('pob')->unsigned()->index()->nullable();
			$table->foreign('pob')
				->references('id')
				->on('origins')
				->onDelete('NO ACTION');

			$table->integer('redouble_id')->unsigned()->index()->nullable();
			$table->foreign('redouble_id')
				->references('id')
				->on('redoubles')
				->onDelete('NO ACTION');

			$table->integer('gender_id')->unsigned()->index();
			$table->foreign('gender_id')
				->references('id')
				->on('genders')
				->onDelete('CASCADE');

			$table->string('high_school_id')->unsigned()->index()->nullable();
			$table->foreign('high_school_id')
				->references('id')
				->on('highSchools')
				->onDelete('NO ACTION');

			$table->integer('origin_id')->unsigned()->index();
			$table->foreign('origin_id')
				->references('id')
				->on('origins')
				->onDelete('NO ACTION');

			$table->integer('candidate_id')->unsigned()->index()->nullable();
			$table->foreign('candidate_id')
				->references('id')
				->on('candidates')
				->onDelete('CASCADE');

			$table->integer('create_uid')->unsigned()->index();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('students');
	}

}
