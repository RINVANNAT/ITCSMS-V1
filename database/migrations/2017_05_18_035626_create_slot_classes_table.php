<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlotClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('group')->nullable();
            $table->integer('degree_id')->unsigned()->index()->nullable();
            $table->integer('course_annual_id')->unsigned()->index()->nullable();
            $table->integer('grade_id')->unsigned()->index()->nullable();
            $table->integer('department_id')->unsigned()->index()->nullable();
            $table->integer('creatåed_uid')->unsigned()->index()->nullable();
            $table->integer('write_uid')->unsigned()->index()->nullable();
            $table->integer('department_option_id')->unsigned()->index()->nullable();
            $table->integer('slot_id')->unsigned()->index()->nullable();
            $table->integer('group_id')->unsigned()->index()->nullable();

            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('CASCADE');


            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('CASCADE');


            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('CASCADE');


            $table->foreign('department_option_id')
                ->references('id')
                ->on('departmentOptions')
                ->onDelete('CASCADE');

            $table->foreign('slot_id')
                ->references('id')
                ->on('slots')
                ->onDelete('CASCADE');

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('CASCADE');

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
        Schema::drop('slot_classes');
    }
}
