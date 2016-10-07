<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSecretroomScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secret_room_score', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('roomcode');
            $table->integer('exam_id');
            $table->integer('score_c');
            $table->integer('score_w');
            $table->integer('score_na');
            $table->integer('sequence');
            $table->integer('order_in_room');
            $table->integer('course_id');
            $table->timestamps();
            $table->string('corrector_name');

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

            $table->enum('result',['Pass','Fail','Reject','Reserve'])->nullable();
            $table->integer('score')->nullable();

            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->onDelete('CASCADE');

            $table->foreign('course_id')
                ->references('id')
                ->on('entranceExamCourses')
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
        Schema::drop('secret_room_score');
    }
}
