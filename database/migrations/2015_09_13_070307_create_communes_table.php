<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('communes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_en');
			$table->string('name_kh');
            $table->boolean('active')->default(true);
			$table->timestamps();

			$table->integer('district_id');
			$table->foreign('district_id')
				->references('id')
				->on('districts')
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
		Schema::drop('communes');
	}

}
