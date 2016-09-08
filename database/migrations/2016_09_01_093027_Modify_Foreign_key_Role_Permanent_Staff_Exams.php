<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyForeignKeyRolePermanentStaffExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {

            $table->foreign('room_id')
                ->references('id')
                ->on('examRooms')
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
        Schema::table('role_permanent_staff_exams', function (Blueprint $table) {
            //
        });
    }
}
