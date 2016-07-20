<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifyColumnRoleTempStaffExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_permanent_staff_exams', function ($table) {
            $table->integer('role_staff_id')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_permanent_staff_exams');
    }
}
