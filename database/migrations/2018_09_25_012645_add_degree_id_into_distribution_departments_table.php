<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDegreeIdIntoDistributionDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribution_departments', function (Blueprint $table) {
            $table->integer('grade_id')->after('academic_year_id')->default(2);
        });

        Schema::table('distribution_department_results', function (Blueprint $table) {
            $table->integer('grade_id')->after('academic_year_id')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distribution_departments', function (Blueprint $table) {
            $table->dropColumn('grade_id');
        });

        Schema::table('distribution_department_results', function (Blueprint $table) {
            $table->dropColumn('grade_id');
        });
    }
}
