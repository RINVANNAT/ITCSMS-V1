<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTableExamAddRegistrationPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {

            $table->date('success_registration_start')->nullable();
            $table->date('success_registration_stop')->nullable();
            $table->date('reserve_registration_start')->nullable();
            $table->date('reserve_registration_stop')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {

            $table->dropColumn('success_registration_start');
            $table->dropColumn('success_registration_stop');
            $table->dropColumn('reserve_registration_start');
            $table->dropColumn('reserve_registration_stop');
        });
    }
}
