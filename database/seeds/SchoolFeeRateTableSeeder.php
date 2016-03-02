<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolFeeRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schoolFees = array(
            array(
                'scholarship_id'=>null,
                'to_pay' => '600',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>1,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>16,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '550',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>1,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>15,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '550',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>1,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>14,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '500',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>1,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>13,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '450',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>1,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>12,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '300',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>3,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>3,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '300',
                'to_pay_currency'=>'$',
                'budget'=>0,
                'budget_currency'=>'$',
                'degree_id'=>3,
                'department_id'=>null,
                'grade_id'=>null,
                'promotion_id'=>2,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
        );

        foreach ($schoolFees as $schoolFee) {
            DB::table('schoolFeeRates')->insert($schoolFee);
        }
    }
}
