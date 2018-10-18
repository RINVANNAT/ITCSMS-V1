<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableGroupSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_group_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timetable_slot_id');
            $table->unsignedInteger('timetable_group_id');
            $table->integer('total_hours');
            $table->integer('total_hours_remain');
            $table->timestamps();

            $table->foreign('timetable_slot_id')
                ->references('id')
                ->on('timetable_slots')
                ->onDelete('cascade');

            $table->foreign('timetable_group_id')
                ->references('id')
                ->on('timetable_groups')
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
        Schema::drop('timetable_group_sessions');
    }
}
