<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedoublesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('redoubles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_en');
			$table->string('name_kh')->nullable();
			$table->string('name_fr')->nullable();
			$table->timestamps();
            $table->boolean('active')->default(true);

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
		Schema::drop('redoubles');
	}

}
