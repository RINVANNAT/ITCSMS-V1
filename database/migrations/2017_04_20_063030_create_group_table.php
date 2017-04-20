<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id')->index()->primaryKey();
            $table->integer('semester_id')->unsigned()->index();
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('NO ACTION');

            $table->string('name_en')->nullable();
            $table->string('name_kh')->nullable();
            $table->string('name_fr')->nullable();
            // $table->string('code')->nullable();
            $table->string('description')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groups');
    }
}
