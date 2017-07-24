<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompetencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('competencies', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name')->index();

            $table->integer('competency_type_id')->unsigned()->index();
            $table->foreign('competency_type_id')
                ->references('id')
                ->on('competency_types')
                ->onDelete('cascade');

            $table->text('properties')->nullable();
            $table->enum('type',["value","condition","calculation"])->default("value");
            $table->text('calculation_rule')->nullable();

            $table->integer('create_uid')->unsigned()->index();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
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
        Schema::drop('competencies');
    }
}
