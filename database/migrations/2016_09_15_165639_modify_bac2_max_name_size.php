<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBac2MaxNameSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("\"studentBac2s\"", function ($table) {
            $table->string('name_kh',255)->nullable()->change();
            $table->string('father_name',255)->nullable()->change();
            $table->string('mother_name',255)->nullable()->change();
            $table->string('can_id',25)->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
