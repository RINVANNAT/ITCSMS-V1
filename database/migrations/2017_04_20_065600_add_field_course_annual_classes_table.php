<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCourseAnnualClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annual_classes', function (Blueprint $table) {
            
            $table->integer('group_id')->unsigned()->index()->nullable();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('NO ACTION');
        });



        Schema::table("studentAnnuals", function (Blueprint $table) {
            
            $table->integer('group_id')->unsigned()->index()->nullable();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
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
