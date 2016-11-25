<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseNameInTableCourseAnnual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {

            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_fr')->nullable();
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
