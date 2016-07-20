<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempEmployees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_kh')->nullable()->index();
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

            $table->integer('payslip_client_id')->unsigned()->nullable();
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
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
        Schema::drop('tempEmployees');
    }
}
