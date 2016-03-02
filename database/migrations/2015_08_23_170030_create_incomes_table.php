<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incomes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('amount_dollar')->nullable();
            $table->string('amount_dollar_kh')->nullable();
            $table->string('amount_riel')->nullable();
            $table->string('amount_riel_kh')->nullable();
			$table->string('number');
            $table->boolean('is_printed')->default(false);
			$table->timestamp('pay_date');
			$table->timestamps();
			$table->softDeletes();

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

            $table->integer('payslip_client_id')->unsigned();
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
                ->onDelete('CASCADE');

            $table->integer('income_type_id')->unsigned();
            $table->foreign('income_type_id')
                ->references('id')
                ->on('incomeTypes')
                ->onDelete('CASCADE');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incomes');
	}

}
