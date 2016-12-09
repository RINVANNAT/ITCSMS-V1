<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePercentateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('percentages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('percent');
            $table->string('unit')->default('%');
            $table->timestamps();

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
        });


        Schema::create('percentage_scores',function(Blueprint $table){
            $table->increments('id')->unsigned();

            $table->integer('percentage_id')->unsigned()->index();
            $table->foreign('percentage_id')->references('id')->on('percentages')->onDelete('cascade');

            $table->integer('score_id')->unsigned()->index();
            $table->foreign('score_id')->references('id')->on('scores')->onDelete('cascade');
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
        Schema::drop('percentages');
    }
}
