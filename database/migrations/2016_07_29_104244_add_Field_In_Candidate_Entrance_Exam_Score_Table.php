<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInCandidateEntranceExamScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidateEntranceExamScores', function (Blueprint $table) {

            $table->integer('candidate_number_in_room')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidateEntranceExamScores', function ($table) {
            $table->dropColumn('candidate_number_in_room');
        });
    }
}
