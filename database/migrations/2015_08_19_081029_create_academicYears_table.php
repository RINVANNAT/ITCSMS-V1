<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicYearsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('academicYears', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_kh')->nullable();
			$table->string('name_en');
			$table->string('name_fr')->nullable();
			$table->string('code')->nullable();
			$table->timestamp('date_start')->nullable();
			$table->timestamp('date_end')->nullable();
			$table->string('description')->nullable();
			$table->timestamps();

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
		Schema::drop('academicYears');
	}

}
