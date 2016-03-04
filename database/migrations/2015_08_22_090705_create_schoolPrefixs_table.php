<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolPrefixsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schoolPrefixs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code',50);
            $table->string('name_en',100)->nullable();
            $table->string('name_kh',100);
            $table->boolean('is_moe')->default(true);
            $table->string('desc',100)->nullable();
            $table->boolean('active')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schoolPrefixs');
    }
}
