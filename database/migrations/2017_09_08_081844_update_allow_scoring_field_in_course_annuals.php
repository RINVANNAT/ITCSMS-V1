<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAllowScoringFieldInCourseAnnuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function ($table) {
            $table->dropColumn('is_allow_scoring');
        });
        Schema::table('course_annuals', function ($table) {
            $table->enum('is_allow_scoring',['yes','no','only_retake'])->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
