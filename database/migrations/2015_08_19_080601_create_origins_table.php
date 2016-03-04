<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('origins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_kh',50)->nullable();
			$table->string('name_en',50)->nullable();
			$table->string('name_fr',50)->nullable();
            $table->string('code',10)->nullable();
            $table->string('prefix',10)->nullable();
            $table->boolean('is_province')->default(true);
            $table->integer('locp_code')->nullable();
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
		Schema::drop('origins');
	}

}
