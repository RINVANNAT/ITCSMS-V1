<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourseForEntranceExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entranceExamCourses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name_kh');
            $table->string('name_en')->nullable();
            $table->string('name_fr')->nullable();
            $table->integer('total_question')->default(30);

            $table->boolean('active')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->integer('create_uid')->unsigned();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('write_uid')->unsigned()->nullable();
            $table->foreign('write_uid')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');

            $table->integer('exam_id')->unsigned();
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
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
        Schema::drop('entranceExamCourses');
    }
}
