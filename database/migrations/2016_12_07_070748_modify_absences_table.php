<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->dropColumn('degree_id');
            $table->dropColumn('grade_id');
            $table->dropColumn('semester_id');
            $table->dropColumn('department_id');
            $table->dropColumn('academic_year_id');
            $table->dropColumn('absence_on');


            $table->integer('num_absence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absences', function (Blueprint $table) {
            //
        });
    }
}
