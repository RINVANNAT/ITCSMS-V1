<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMathPhysicChemLogicTotalFromCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidates', function ($table) {
            $table->dropColumn('phys_chem_c');
            $table->dropColumn('phys_chem_w');
            $table->dropColumn('phys_chem_na');

            $table->dropColumn('logic_c');
            $table->dropColumn('logic_w');
            $table->dropColumn('logic_na');

            $table->dropColumn('math_c');
            $table->dropColumn('math_w');
            $table->dropColumn('math_na');

            $table->dropColumn('total_s');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('candidates');
    }
}
