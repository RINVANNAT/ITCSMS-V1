<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableGroupSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_group_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('slot_id');
            $table->unsignedInteger('timetable_group_id');
            $table->integer('room_id')->nullable();
            $table->integer('total_hours');
            $table->integer('total_hours_remain');
            $table->timestamps();

            $table->foreign('slot_id')
                ->references('id')
                ->on('slots')
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
        Schema::drop('timetable_group_slots');
    }
}
