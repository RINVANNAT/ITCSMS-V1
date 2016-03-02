<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBac2ProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bac2Programs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name_en')->nullable();
            $table->string('name_kh');
            $table->boolean('is_grade12')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bac2Programs');
    }
}
