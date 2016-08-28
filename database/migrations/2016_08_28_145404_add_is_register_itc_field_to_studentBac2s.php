<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRegisterItcFieldToStudentBac2s extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studentBac2s', function (Blueprint $table) {
            $table->boolean('is_register_itc')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studentBac2s', function (Blueprint $table) {
            $table->dropColumn('is_register_itc');
        });
    }
}
