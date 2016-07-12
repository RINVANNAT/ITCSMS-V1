<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmptyDataRoomtypeBuildingAndResetSequence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DELETE from "roomTypes"');
        DB::statement('DELETE from "buildings"');
        DB::statement('ALTER SEQUENCE "roomTypes_id_seq" RESTART WITH 1');
        DB::statement('ALTER SEQUENCE "buildings_id_seq" RESTART WITH 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing can be reversed
    }
}
