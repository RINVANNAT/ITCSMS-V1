<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRelationRoomFromExamRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DELETE FROM "examRooms"');

        Schema::table('examRooms', function ($table) {
            $table->dropColumn('room_id');;
            $table->string('name',100);
            $table->string('description',100)->nullable();

            $table->integer('room_type_id')->unsigned()->nullable()->index();
            $table->foreign('room_type_id')
                ->references('id')
                ->on('roomTypes')
                ->onDelete('CASCADE');

            $table->integer('department_id')->unsigned()->nullable()->index()->nullable();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('NO ACTION');

            $table->integer('building_id')->unsigned()->nullable()->index();
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
