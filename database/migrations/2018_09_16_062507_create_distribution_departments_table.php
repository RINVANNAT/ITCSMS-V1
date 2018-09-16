<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_annual_id');
            $table->integer('department_id');
            $table->integer('department_option_id')->nullable();
            $table->integer('priority');
            $table->float('score');
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
        Schema::drop('distribution_departments');
    }
}
