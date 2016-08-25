<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExamRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examRooms', function ($table) {
            $table->increments('id');
            $table->integer('nb_chair_exam');
            $table->string('roomcode')->nullable();
            $table->timestamps();

            $table->integer('room_id')->unsigned()->index();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('CASCADE');

            $table->integer('exam_id')->unsigned()->index();
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->onDelete('CASCADE');

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
        Schema::table('examRooms', function($table) {
            $table->dropColumn('code');
        });
    }
}
