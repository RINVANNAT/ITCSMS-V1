<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnGeneralRemarkToStudentAnnual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studentAnnuals', function (Blueprint $table) {
            $table->string('general_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studentAnnuals', function (Blueprint $table) {
            $table->dropColumn('general_remark');
        });
    }
}
