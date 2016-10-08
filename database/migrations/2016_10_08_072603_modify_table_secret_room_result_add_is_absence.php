<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTableSecretRoomResultAddIsAbsence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('secret_room_result', function (Blueprint $table) {

            $table->boolean('is_absence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('secret_room_result', function (Blueprint $table) {
            $table->dropColumn('is_absence');
        });
    }
}
