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
            $table->string('person');
            $table->string('company');
            $table->string('address');
            $table->string('phone');
            $table->string('hot_line');
            $table->string('e_mail_address');
            $table->string('web');
            $table->string('title');
            $table->string('training_field');

            $table->dateTime('issue_date')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');

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
