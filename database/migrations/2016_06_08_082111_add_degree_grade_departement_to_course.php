<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDegreeGradeDepartementToCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->unsignedInteger('degree_id')->nullable();
            $table->unsignedInteger('grade_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('semester_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('degree_id')->nullable();
            $table->dropColumn('grade_id')->nullable();
            $table->dropColumn('department_id')->nullable();
            $table->dropColumn('semester_id')->nullable();
        });
    }
}
