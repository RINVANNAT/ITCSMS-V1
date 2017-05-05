<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeats', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
        });

        Schema::create('department_event', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('department_id');
            $table->integer('event_id');

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('CASCADE');

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
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
        Schema::drop('department_event');
        Schema::drop('repeats');
    }
}
