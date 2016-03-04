<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_kh')->nullable();
			$table->string('name_latin');
            $table->string('email');
            $table->string('phone');
			$table->timestamp('birthdate')->nullable();
			$table->string('address')->nullable();
			$table->boolean('active')->default(true);
			$table->timestamps();

			$table->integer('gender_id')->unsigned()->index();
			$table->foreign('gender_id')
				->references('id')
				->on('genders')
				->onDelete('CASCADE');

            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
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


            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('payslip_client_id')->unsigned()->nullable();
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
                ->onDelete('NO ACTION');
		});

        Schema::create('employee_role',function(Blueprint $table){
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->integer('employee_id')->unsigned()->index();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
        Schema::drop('employee_role');
	}

}
