<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        if(!Schema::hasTable('slots')) {

            Schema::create('slots', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('time_tp')->default(0);
                $table->integer('time_td')->default(0);
                $table->integer('time_course')->default(0);
                $table->integer('course_annual_id')->unsigned();
                $table->integer('course_session_id')->unsigned();
                $table->integer('lecturer_id')->unsigned()->nullable();
                $table->integer('responsible_department_id')->unsigned()->nullable();
                $table->integer('group_id')->unsigned()->nullable();
                $table->double('time_used')->nullable();
                $table->double('time_remaining')->nullable();

                $table->integer('created_uid')->unsigned();
                $table->integer('write_uid')->unsigned()->nullable();


                $table->foreign('course_annual_id')
                    ->references('id')
                    ->on('course_annuals')
                    ->onDelete('cascade');

                $table->foreign('course_session_id')
                    ->references('id')
                    ->on('course_sessions')
                    ->onDelete('cascade');

                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');

                $table->timestamps();
            });

        }


        // Remove time_used and time_remaining columns from course_sessions table.
       /* Schema::table('course_sessions', function (Blueprint $table) {
            $table->dropColumn(['time_used', 'time_remaining']);
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('slots');
    }
}
