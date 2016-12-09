<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            //

            $table->dropColumn('percentage');
            $table->dropColumn('exam_name');
            $table->dropColumn('score10');
            $table->dropColumn('score30');
            $table->dropColumn('score60');
            $table->dropColumn('reexam');

            $table->double('score_absence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            //
        });
    }
}
