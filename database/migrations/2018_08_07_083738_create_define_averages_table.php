<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefineAveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('define_averages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id');
            $table->integer('department_id');
            $table->integer('semester_id');
            $table->integer('option_id')->nullable();
            $table->integer('degree_id');
            $table->integer('grade_id');
            $table->float('value')->default(50.00);
            $table->integer('create_uid');
            $table->integer('write_uid');
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
        Schema::drop('define_averages');
    }
}
