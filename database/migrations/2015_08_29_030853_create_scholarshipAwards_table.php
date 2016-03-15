<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipAwardsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scholarshipAwards', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('scholarship_id');
            $table->string('award');
            $table->integer('degree_id');
            $table->integer('promotion_id');
            $table->integer('academic_year_id');

            $table->string('description')->nullable();
            $table->timestamps();
            $table->boolean('active')->default(true);

            $table->foreign('scholarship_id')
                ->references('id')
                ->on('scholarships')
                ->onDelete('CASCADE');
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('CASCADE');

            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->onDelete('CASCADE');

            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');

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
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('scholarshipAwards');
	}

}
