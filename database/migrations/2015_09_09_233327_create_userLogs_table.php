<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userLogs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('model');
            $table->string('action');
            $table->text('data');
            $table->string('ip_address', 64);
            $table->string('user_agent');
            $table->timestamps();
            $table->boolean('is_developer')->default(false);

            $table->integer('create_uid')->unsigned()->index();
            $table->foreign('create_uid')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userLogs');
    }
}
