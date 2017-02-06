<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFieldTableCourseAnnuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_annuals', function (Blueprint $table) {

            $table->dropForeign('course_annuals_department_id_foreign');
            $table->dropColumn('department_id');

            $table->dropForeign('course_annuals_degree_id_foreign');
            $table->dropColumn('degree_id');

            $table->dropForeign('course_annuals_grade_id_foreign');
            $table->dropColumn('grade_id');

            $table->dropForeign('course_annuals_department_option_id_foreign');
            $table->dropColumn('department_option_id');

            $table->dropColumn('group');
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
