<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreRuleToCourseAnnualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            //

            $table->integer('score_percentage_column_1')->nullable()->default(10);
            $table->integer('score_percentage_column_2')->nullable()->default(30);
            $table->integer('score_percentage_column_3')->nullable()->default(60);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            //
            $table->dropColumn('score_percentage_column_1');
            $table->dropColumn('score_percentage_column_2');
            $table->dropColumn('score_percentage_column_3');

        });
    }
}
