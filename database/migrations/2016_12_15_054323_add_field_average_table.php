<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldAverageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('averages', function (Blueprint $table) {


            $table->integer('total_average_id')->unsigned()->index()->nullable();
            $table->foreign('total_average_id')->references('id')->on('totalAverages')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('averages', function (Blueprint $table) {
            //
        });
    }
}
