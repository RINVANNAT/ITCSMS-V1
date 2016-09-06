<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCorrectorNameInCandidateEntranceExamScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidateEntranceExamScores', function (Blueprint $table) {
            $table->string('corrector')->nullable();
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
            $table->dropColumn('corrector');
        });
    }
}
