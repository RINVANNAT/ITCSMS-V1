<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_event_id')->unsigned();
            $table->string('title');
            $table->boolean('allDay')->default(true);
            $table->boolean('study')->default(false);
            $table->boolean('fix')->default(true);
            $table->timestamps();

            $table->foreign('category_event_id')->references('id')->on('category_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
