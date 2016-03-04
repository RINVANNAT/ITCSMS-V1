<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rooms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name',10);
			$table->timestamps();
			$table->integer('nb_desk')->nullable();
            $table->integer('nb_chair')->nullable();
            $table->integer('nb_chair_exam')->nullable();

            $table->string('description',100)->nullable();
            $table->string('size',10)->nullable();
			$table->boolean('active')->default(true);

            $table->integer('room_type_id')->unsigned()->index();
            $table->foreign('room_type_id')
                ->references('id')
                ->on('roomTypes')
                ->onDelete('CASCADE');

			$table->integer('department_id')->unsigned()->index()->nullable();
			$table->foreign('department_id')
				->references('id')
				->on('departments')
				->onDelete('NO ACTION');

			$table->integer('building_id')->unsigned()->index();
			$table->foreign('building_id')
				->references('id')
				->on('buildings')
				->onDelete('NO ACTION');

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
		Schema::drop('rooms');
	}

}
