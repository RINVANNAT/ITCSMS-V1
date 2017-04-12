<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('event_year', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('event_id');
            $table->integer('year_id');

            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('CASCADE');

            $table->foreign('year_id')
                ->references('id')
                ->on('years')
                ->onDelete('CASCADE');

            $table->dateTime('start');
            $table->dateTime('end');

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
        Schema::drop('event_year');
        Schema::drop('years');
    }
}
