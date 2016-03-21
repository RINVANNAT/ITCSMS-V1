<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('candidates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_latin');
            $table->string('name_kh');
            $table->string('register_id')->nullable();
			$table->timestamp('dob')->nullable();
			$table->string('mcs_no')->nullable();
			$table->string('can_id')->nullable();

            $table->string('phone',100)->nullable();
            $table->string('email',100)->nullable();

            $table->string('address')->nullable();
            $table->string('address_current')->nullable();

			$table->timestamps();
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_register')->default(false);
			$table->enum('result',['Pending','Pass','Fail','Rejected'])->default('Pending');
            $table->enum('register_from',['ITC', 'Ministry'])->default('ITC');

            $table->integer('math_c')->nullable();
            $table->integer('math_w')->nullable();
            $table->integer('math_na')->nullable();
            $table->integer('phys_chem_c')->nullable();
            $table->integer('phys_chem_w')->nullable();
            $table->integer('phys_chem_na')->nullable();
            $table->integer('logic_c')->nullable();
            $table->integer('logic_w')->nullable();
            $table->integer('logic_na')->nullable();

            $table->integer('total_s')->nullable();
            $table->float('average')->nullable();

            $table->float('bac_percentile')->nullable();
            $table->boolean('active')->default(true);

            $table->string('highschool_id')->index()->nullable();
            $table->foreign('highschool_id')
                ->references('id')
                ->on('highSchools')
                ->onDelete('NO ACTION');

            $table->integer('promotion_id')->unsigned()->index();
            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->onDelete('CASCADE');

            $table->integer('bac_total_grade')->unsigned()->index()->nullable();
            $table->foreign('bac_total_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->integer('bac_math_grade')->unsigned()->index()->nullable();
            $table->foreign('bac_math_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->integer('bac_phys_grade')->unsigned()->index()->nullable();
            $table->foreign('bac_phys_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->integer('bac_chem_grade')->unsigned()->index()->nullable();
            $table->foreign('bac_chem_grade')
                ->references('id')
                ->on('gdeGrades')
                ->onDelete('CASCADE');

            $table->integer('bac_year')->unsigned()->index()->nullable();
            $table->foreign('bac_year')
                ->references('id')
                ->on('academicYears')
                ->onDelete('CASCADE');

            $table->integer('province_id')->unsigned()->index()->nullable();
            $table->foreign('province_id')
                ->references('id')
                ->on('origins')
                ->onDelete('CASCADE');

            $table->integer('pob')->unsigned()->index()->nullable();
            $table->foreign('pob')
                ->references('id')
                ->on('origins')
                ->onDelete('CASCADE');


            $table->integer('gender_id')->unsigned()->index();
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onDelete('CASCADE');

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

			$table->integer('academic_year_id')->unsigned()->index()->nullable();
			$table->foreign('academic_year_id')
				->references('id')
				->on('academicYears')
				->onDelete('NO ACTION');

            $table->integer('degree_id')->unsigned()->index();
            $table->foreign('degree_id')
                ->references('id')
                ->on('degrees')
                ->onDelete('NO ACTION');

            $table->integer('grade_id')->unsigned()->default(1)->index();
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('NO ACTION');

            $table->integer('department_id')->unsigned()->index()->nullable(); // It will be defined when student passed.
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('NO ACTION');

            $table->integer('studentBac2_id')->unsigned()->index()->nullable();
            $table->foreign('studentBac2_id')
                ->references('id')
                ->on('studentBac2s')
                ->onDelete('NO ACTION');

            $table->integer('exam_id')->unsigned()->index()->nullable();
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->onDelete('CASCADE');

            $table->integer('payslip_client_id')->unsigned()->nullable();
            $table->foreign('payslip_client_id')
                ->references('id')
                ->on('payslipClients')
                ->onDelete('NO ACTION');


		});

        Schema::create('candidate_department', function(Blueprint $table)
        {
            $table->integer('candidate_id')->unsigned()->index();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');

            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->integer('rank')->unsigned();
            $table->boolean('is_success')->default(false);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('candidate_department');
        Schema::drop('candidates');
	}

}
