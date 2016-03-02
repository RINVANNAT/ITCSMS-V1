<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //I1T
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('degree_id');
            $table->unsignedInteger('grade_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('academic_year_id');
            $table->unsignedInteger('semester_id')->nullable();;
            $table->unsignedInteger('course_annual_id');
            $table->unsignedInteger('student_annual_id');
            $table->float('score10');
            $table->float('score30');
            $table->float('score60');

            $table->timestamps();

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

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scores');
    }
}
