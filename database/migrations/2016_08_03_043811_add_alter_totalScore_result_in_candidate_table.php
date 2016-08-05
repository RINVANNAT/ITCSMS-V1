<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddAlterTotalScoreResultInCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::statement('ALTER TABLE candidates ADD total_score INTEGER ');
            DB::statement('ALTER TABLE candidates DROP CONSTRAINT candidates_result_check;');
            DB::statement('ALTER TABLE candidates ADD CONSTRAINT candidates_result_check CHECK (result::TEXT = ANY (ARRAY[\'Pending\'::CHARACTER VARYING, \'Pass\'::CHARACTER VARYING, \'Fail\'::CHARACTER VARYING, \'Reserve\'::CHARACTER VARYING, \'Reject\'::CHARACTER VARYING]::TEXT[]))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
