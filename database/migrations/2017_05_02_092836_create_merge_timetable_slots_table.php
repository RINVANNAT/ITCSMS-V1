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

        if(!Schema::hasTable('merge_timetable_slots')) {

            Schema::create('merge_timetable_slots', function (Blueprint $table) {
                $table->increments('id');
                $table->dateTime('start')->unsigned();
                $table->dateTime('end')->unsigned();
                $table->timestamps();
            });
        }

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
