<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('districts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_kh');
			$table->string('name_en');
            $table->boolean('active')->default(true);
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

			$table->integer('province_id')->unsigned()->index()->nullable();
			$table->foreign('province_id')
				->references('id')
				->on('origins')
				->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('districts');
	}

}
