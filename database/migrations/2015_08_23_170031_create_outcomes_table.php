<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutcomesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outcomes', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('amount_dollar')->nullable();
            $table->integer('amount_riel')->nullable();
            $table->string('amount_kh');
            $table->string('number')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_printed')->default(false);
            $table->timestamp('pay_date');
            $table->timestamps();
            $table->boolean('active')->default(true);
            $table->string('attachment_name')->nullable();

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

            $table->integer('payslip_client_id')->unsigned()->nullable();
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
                ->onDelete('CASCADE');

            $table->integer('outcome_type_id')->unsigned()->nullable();
            $table->foreign('outcome_type_id')
                ->references('id')
                ->on('outcomeTypes')
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
		Schema::drop('outcomes');
	}

}
