<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title_kh');
            $table->string('title_en')->nullable();
            $table->string('title_fr')->nullable();

            $table->timestamps();

            $table->integer('create_uid')->unsigned()->index();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('titles');
    }
}
