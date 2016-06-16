<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function ($table) {
            $table->dropColumn('number_room_controller');
            $table->dropColumn('number_floor_controller');
            $table->dropColumn('math_score_quote');
            $table->dropColumn('phys_chem_score_quote');
            $table->dropColumn('logic_score_quote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function($table) {

            $table->integer('number_room_controller')->default(2);
            $table->integer('number_floor_controller')->default(10);
            $table->integer('math_score_quote')->nullable();
            $table->integer('phys_chem_score_quote')->nullable();
            $table->integer('logic_score_quote')->nullable();
        });
    }
}
