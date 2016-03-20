<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporting', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->enum('status',['Pending','In Progress','Done','Rejected'])->default('Pending');

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

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reporting');
    }
}
