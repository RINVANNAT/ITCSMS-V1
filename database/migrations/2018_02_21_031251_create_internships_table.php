<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ref_number')->nullable();
            $table->bigInteger('number');
            $table->string('subject');
            $table->string('internship_title');
            $table->dateTime('date')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('contact_name');
            $table->text('contact_detail');
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
        Schema::drop('internships');
    }
}
