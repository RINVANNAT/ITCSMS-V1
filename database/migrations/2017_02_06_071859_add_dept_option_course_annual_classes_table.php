<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeptOptionCourseAnnualClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annual_classes', function (Blueprint $table) {

            $table->integer('department_option_id')->unsigned()->index()->nullable();
            $table->foreign('department_option_id')
                ->references('id')
                ->on('departmentOptions')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_annual_classes', function (Blueprint $table) {
            //
        });
    }
}
