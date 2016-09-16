<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCandidatesFromMoeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("candidatesFromMoeys", function(Blueprint $table) {
            $table->increments('id')->index();
            $table->string('can_id')->index();
            $table->integer('bac_year')->unsigned()->index();

            $table->foreign('bac_year')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("candidatesFromMoeys");
    }
}
