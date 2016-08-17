<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcademicYearIdOnTempEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tempEmployees', function (Blueprint $table) {

            $table->integer('academic_year_id')->unsigned()->nullable();
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');

            $table->unique(array('name_latin', 'academic_year_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tempEmployees', function ($table) {
            $table->dropColumn('academic_year_id');
            $table->dropUnique(array('name_latin', 'academic_year_id'));
        });
    }
}
