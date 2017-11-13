<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCertificateReferences extends Migration
{
    /**
     * Composite Primary keys: student_annual_id, course_annual_id, ref_number
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_references', function (Blueprint $table) {
            $table->integer('student_annual_id')->index();
            $table->foreign('student_annual_id')
                ->references('id')
                ->on('studentAnnuals')
                ->onDelete('CASCADE');

            $table->integer('course_annual_id')->index();
            $table->foreign('course_annual_id')
                ->references('id')
                ->on('course_annuals')
                ->onDelete('CASCADE');

            $table->integer('ref_number')->index()->unique();

            $table->primary(['student_annual_id', 'course_annual_id','ref_number']);

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
        Schema::drop('certificate_references');
    }
}
