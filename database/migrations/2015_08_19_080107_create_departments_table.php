<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('departments', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name_kh')->nullable();
            $table->string('name_en');
            $table->string('name_fr')->nullable();
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_specialist')->default(true);
			$table->boolean('active')->default(true);
            $table->timestamps();

			$table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')
				->references('id')
				->on('departments')
				->onDelete('cascade');

			$table->integer('school_id')->unsigned();
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');

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
		Schema::drop('departments');
	}

}
