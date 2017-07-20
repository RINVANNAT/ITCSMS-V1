<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrintedCertificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printed_certificate', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('student_annual_id')->index();
            $table->foreign('student_annual_id')
                ->references('id')
                ->on('studentAnnuals')
                ->onDelete('cascade');

            $table->integer('course_annual_id')->index();
            $table->foreign('course_annual_id')
                ->references('id')
                ->on('course_annuals')
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
        Schema::drop('printed_certificate');
    }
}
