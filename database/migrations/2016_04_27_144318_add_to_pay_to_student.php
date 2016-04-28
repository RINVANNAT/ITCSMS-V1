<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
class AddToPayToStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studentAnnuals', function($table)
        {
            $table->integer('to_pay')->nullable();
            $table->enum('to_pay_currency',['$','៛'])->default('$')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studentAnnuals', function($table)
        {
            $table->dropColumn(array('to_pay'));
            $table->dropColumn(array('to_pay_currency'));
        });
    }
}
