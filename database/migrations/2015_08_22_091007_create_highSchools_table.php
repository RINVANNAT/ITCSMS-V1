<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highSchools', function(Blueprint $table)
        {
            $table->string('id',20)->primary();
            $table->string('name_en')->nullable();
            $table->integer('province_id')->unsigned()->index();
            $table->integer('d_id')->nullable();
            $table->integer('c_id')->nullable();
            $table->integer('v_id')->nullable();
            $table->integer('s_id')->nullable();
            $table->integer('ps_id')->nullable();
            $table->string('name_kh')->nullable();
            $table->integer('prefix_id')->unsigned()->index();
            $table->integer('valid')->default(1);
            $table->integer('is_no_school')->default(1);
            $table->integer('locp_code')->nullable();
            $table->integer('locd_code')->nullable();
            $table->integer('locc_code')->nullable();
            $table->integer('locv_code')->nullable();
            $table->boolean('active')->default(true);

            $table->foreign('province_id')
                ->references('id')
                ->on('origins')
                ->onDelete('CASCADE');

            $table->foreign('prefix_id')
                ->references('id')
                ->on('schoolPrefixs')
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
        Schema::drop('highSchools');
    }
}
