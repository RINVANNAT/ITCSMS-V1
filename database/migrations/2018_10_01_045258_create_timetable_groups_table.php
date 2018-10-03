<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('code');
            $table->string('description');
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
        Schema::drop('timetable_groups');
    }
}
