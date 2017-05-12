<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMergeTimetableSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merge_timetable_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_timetable_slot_id')->unsigned();
            $table->integer('timetable_slot_id')->unsigned();

            $table->foreign('timetable_slot_id')
                ->references('id')
                ->on('timetable_slots')
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
        Schema::drop('merge_timetable_slots');
    }
}
