<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldResitStudentAnnualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resit_student_annuals', function (Blueprint $table) {

            $table->integer('semester_id')->unsigned()->index()->nullable();
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
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
        Schema::table('resit_student_annuals', function (Blueprint $table) {
            //
        });
    }
}
