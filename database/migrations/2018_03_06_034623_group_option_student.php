<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupOptionStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_option_student', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('priority')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('NO ACTION');

            $table->integer('group_option_id')->unsigned()->index();
            $table->foreign('group_option_id')
                ->references('id')
                ->on('group_options')
                ->onDelete('NO ACTION');

            $table->timestamps();
            $table->integer('create_uid')->unsigned()->index()->nullable();
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
        Schema::drop('group_option_student');
    }
}
