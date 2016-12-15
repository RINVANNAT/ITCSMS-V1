<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFieldAverageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('averages', function (Blueprint $table) {
            //
        });


        Schema::create('average_scores',function(Blueprint $table){
            $table->increments('id')->unsigned();

            $table->integer('average_id')->unsigned()->index();
            $table->foreign('average_id')->references('id')->on('averages')->onDelete('cascade');

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
        Schema::table('average_scores', function (Blueprint $table) {
            //
        });
    }
}
