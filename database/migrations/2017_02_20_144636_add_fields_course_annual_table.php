<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsCourseAnnualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {
            $table->boolean('is_counted_absence')->default(true);
            $table->boolean('is_counted_creditability')->default(true);
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
        });
    }
}
