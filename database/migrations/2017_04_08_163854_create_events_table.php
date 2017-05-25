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
            $table->integer('repeat_id')->nullable();
            $table->string('title');
            $table->string('description');
            $table->boolean('public')->default(false);
            $table->boolean('study')->default(false);
            $table->boolean('allDay')->default(true);
            $table->integer('created_uid');
            $table->integer('updated_uid');
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
        Schema::drop('events');
    }
}
