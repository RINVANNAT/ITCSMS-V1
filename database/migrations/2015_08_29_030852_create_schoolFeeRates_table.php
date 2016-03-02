<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolFeeRatesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schoolFeeRates', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('scholarship_id')->nullable();
            $table->integer('to_pay')->default(0);
            $table->enum('to_pay_currency',['$','៛'])->nullable();
            $table->integer('budget')->default(0);
            $table->enum('budget_currency',['$','៛'])->nullable();
            $table->integer('degree_id');
            $table->integer('department_id')->nullable(); // If this isn't provided, it will apply for all departments
            $table->integer('grade_id')->nullable(); // If this isn't provided, it will apply for all grades
            $table->integer('promotion_id');
            $table->integer('academic_year_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('scholarship_id')
                ->references('id')
                ->on('scholarships')
                ->onDelete('CASCADE');
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('CASCADE');
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('CASCADE');
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
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
		Schema::drop('schoolFeeRates');
	}

}
