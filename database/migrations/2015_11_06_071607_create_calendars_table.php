<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendars', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event');
			$table->timestamp('start');
			$table->timestamp('stop');
			$table->timestamps();
            $table->boolean('active')->default(true);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calendars');
	}

}
