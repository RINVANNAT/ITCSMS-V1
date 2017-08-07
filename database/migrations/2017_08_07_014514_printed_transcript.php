<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrintedTranscript extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printed_transcripts', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('type');
            $table->integer('academic_year_id')->index();
            $table->integer('student_annual_id')->index();
            $table->foreign('student_annual_id')
                ->references('id')
                ->on('studentAnnuals')
                ->onDelete('cascade');

            $table->integer('create_uid')->unsigned()->index();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('printed_transcripts');
    }
}
