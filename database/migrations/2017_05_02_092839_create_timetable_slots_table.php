<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimetableSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('timetable_id')->unsigned();
            $table->integer('course_session_id')->unsigned();
            $table->integer('slot_id')->unsigned();
            $table->integer('room_id')->nullable();
            $table->integer('group_merge_id')->unsiged();

            $table->string('course_name');
            $table->string('teacher_name');
            $table->string('type');
            $table->double('durations')->unsigned();

            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('created_uid');
            $table->integer('updated_uid');

            $table->foreign('timetable_id')
                ->references('id')
                ->on('timetables')
                ->onDelete('cascade');

            $table->foreign('course_session_id')
                ->references('id')
                ->on('course_sessions')
                ->onDelete('cascade');

            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');

            /*$table->foreign('group_merge_id')
                ->references('id')
                ->on('merge_timetable_slots')
                ->onDelete('cascade');*/

            $table->foreign('slot_id')
                ->references('id')
                ->on('slots')
                ->onDelete('cascade');


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
        Schema::drop('timetable_slots');
    }
}
