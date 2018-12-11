<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNumberColumnInternshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->unsignedBigInteger('number')->nullable();
            $table->unsignedBigInteger('academic_year_id')->default(2018);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn('number')->nullable();
            $table->dropColumn('academic_year_id')->nullable();
        });
    }
}
