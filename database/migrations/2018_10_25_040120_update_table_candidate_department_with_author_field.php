<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableCandidateDepartmentWithAuthorField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_department', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('create_uid')->unsigned()->index()->nullable();
            $table->integer('write_uid')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_department', function (Blueprint $table) {
            $table->dropColumn('create_uid');
            $table->dropColumn('write_uid');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
