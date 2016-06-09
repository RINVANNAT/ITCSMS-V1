<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresRelation extends Migration
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
            //chheang todo testing if score still work
            
            //course_annual_id';
            //student_annual_id;
            //academic_year_id');
//            $table->foreign('student_annual_id')
//                ->references('id')
//                ->on('studentAnnuals')
//                ->onDelete('NO ACTION');
//
//            $table->foreign('course_annual_id')
//                ->references('id')
//                ->on('courseAnnuals')
//                ->onDelete('NO ACTION');
//
//            $table->foreign('academic_year_id')
//                ->references('id')
//                ->on('academicYears')
//                ->onDelete('NO ACTION');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
