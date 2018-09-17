<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionDepartmentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_department_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id');
            $table->bigInteger('student_annual_id');
            $table->integer('department_id');
            $table->integer('department_option_id')->nullable();
            $table->float('total_score');
            $table->integer('priority');
            $table->timestamps();

            $table->foreign('student_annual_id')
                ->references('id')
                ->on('studentAnnuals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('distribution_department_results');
    }
}
