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
                'degree_id'=>1,
                'promotion_id'=>35,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),

                'grades' => array(
                    1,2,3,4,5
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '550',
                'to_pay_currency'=>'$',
                'degree_id'=>1,
                'promotion_id'=>34,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2,3,4,5
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )

            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '550',
                'to_pay_currency'=>'$',
                'degree_id'=>1,
                'promotion_id'=>33,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2,3,4,5
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '500',
                'to_pay_currency'=>'$',
                'degree_id'=>1,
                'promotion_id'=>32,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2,3,4,5
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '450',
                'to_pay_currency'=>'$',
                'degree_id'=>1,
                'promotion_id'=>31,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2,3,4,5
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '300',
                'to_pay_currency'=>'$',
                'degree_id'=>2,
                'promotion_id'=>22,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
            array(
                'scholarship_id'=>null,
                'to_pay' => '300',
                'to_pay_currency'=>'$',
                'degree_id'=>2,
                'promotion_id'=>21,
                'academic_year_id'=>null,
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
                'grades' => array(
                    1,2
                ),
                'departments' => array(
                    1,2,3,4,5,6,7,8
                )
            ),
        );

        foreach ($schoolFees as $schoolFee) {

            $fee = new \App\Models\SchoolFeeRate();
            $fee->scholarship_id = $schoolFee['scholarship_id'];
            $fee->to_pay = $schoolFee['to_pay'];
            $fee->to_pay_currency = $schoolFee['to_pay_currency'];
            $fee->degree_id = $schoolFee['degree_id'];
            $fee->promotion_id = $schoolFee['promotion_id'];
            $fee->academic_year_id = $schoolFee['academic_year_id'];
            $fee->create_uid = $schoolFee['create_uid'];
            $fee->updated_at = $schoolFee['updated_at'];

            if($fee->save()){
                $fee->grades()->sync($schoolFee['grades']);
                $fee->departments()->sync($schoolFee['departments']);
            }
        }
    }
}
