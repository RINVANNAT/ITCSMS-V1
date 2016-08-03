<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCandidateRegisterIdToInteger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //DB::statement('DELETE FROM candidates');    --> need to empty candidates first
        DB::statement('ALTER TABLE candidates ALTER COLUMN register_id TYPE integer USING (register_id::integer);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidates', function ($table) {
            $table->string('register_id')->nullable()->change();
        });
    }
}
