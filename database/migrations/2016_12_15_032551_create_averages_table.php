<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('averages', function (Blueprint $table) {

            $table->increments('id');
            $table->double('average')->nullable();

            $table->integer('course_annual_id')->unsigned()->index();
            $table->foreign('course_annual_id')->references('id')->on('course_annuals')->onDelete('cascade');

            $table->integer('student_annual_id')->unsigned()->index();
            $table->foreign('student_annual_id')->references('id')->on('studentAnnuals')->onDelete('cascade');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('create_uid')->unsigned()->index()->nullable();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

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
        Schema::drop('averages');
    }
}
