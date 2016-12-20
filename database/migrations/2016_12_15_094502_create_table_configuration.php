<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer('create_uid')->unsigned()->index()->nullable();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->string('key');
            $table->string('value');
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('configurations');
    }
}
