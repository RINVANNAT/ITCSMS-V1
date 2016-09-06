<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContrainUniqueFieldRolePermanentStaffExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {

            $table->unique(array('employee_id', 'role_staff_id', 'exam_id', 'room_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {
            $table->dropUnique(array('employee_id', 'role_staff_id', 'exam_id', 'room_id'));
        });
    }
}
