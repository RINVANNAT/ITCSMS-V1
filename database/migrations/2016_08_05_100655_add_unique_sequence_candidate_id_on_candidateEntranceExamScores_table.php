<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueSequenceCandidateIdOnCandidateEntranceExamScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidateEntranceExamScores', function (Blueprint $table) {

            $table->unique(array('sequence', 'candidate_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidateEntranceExamScores', function (Blueprint $table) {

            $table->dropUnique(array('sequence', 'candidate_id'));
        });


    }
}
