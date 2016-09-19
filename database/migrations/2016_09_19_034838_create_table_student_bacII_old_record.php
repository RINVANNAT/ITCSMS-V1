<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentBacIIOldRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_bac2s_old_record', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('can_id',25);
            $table->integer('mcs_no');
            $table->integer('province_id')->unsigned()->index();
            $table->string('name_kh',100)->index();
            $table->timestamp('dob');
            $table->integer('gender_id')->unsigned()->index();
            $table->string('father_name',100)->nullable();
            $table->string('mother_name',100)->nullable();
            $table->integer('pob')->unsigned()->index();
            $table->string('highschool_id')->index();
            $table->integer('room')->nullable();
            $table->integer('seat')->nullable();
            $table->integer('bac_math_grade')->unsigned()->index()->nullable();
            $table->integer('bac_chem_grade')->unsigned()->index()->nullable();
            $table->integer('bac_phys_grade')->unsigned()->index()->nullable();
            $table->float('percentile');
            $table->integer('grade')->unsigned()->index();
            $table->integer('program')->unsigned()->index();
            $table->string('desc')->nullable();

            $table->integer('bac_year')->unsigned()->index();
            $table->enum('status',['ITC','Ministry'])->default('ITC');
            $table->boolean('is_registered')->default(false);
            $table->boolean('active')->default(true);


            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onDelete('CASCADE');

            $table->foreign('highschool_id')
                ->references('id')
                ->on('highSchools')
                ->onDelete('CASCADE');


            $table->foreign('grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->foreign('bac_math_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->foreign('bac_phys_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->foreign('bac_chem_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');


            $table->foreign('bac_year')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');

            $table->foreign('province_id')
                ->references('id')
                ->on('origins')
                ->onDelete('CASCADE');

            $table->foreign('pob')
                ->references('id')
                ->on('origins')
                ->onDelete('CASCADE');

            $table->foreign('program')
                ->references('id')
                ->on('bac2Programs')
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
        Schema::drop('student_bac2s_old_record');
    }
}
