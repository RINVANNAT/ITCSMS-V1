<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcademicYearIdTableRedoubleStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redouble_student', function (Blueprint $table) {

            $table->integer('academic_year_id')->unsigned()->index()->nullable();
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redouble_student', function (Blueprint $table) {
            //
        });
    }
}
