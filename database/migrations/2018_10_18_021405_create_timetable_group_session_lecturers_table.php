<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableGroupSessionLecturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_group_session_lecturers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timetable_group_session_id');
            $table->unsignedInteger('lecturer_id');
            $table->timestamps();

            $table->foreign('timetable_group_session_id')
                ->references('id')
                ->on('timetable_group_sessions')
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
        Schema::drop('timetable_group_session_lecturers');
    }
}
