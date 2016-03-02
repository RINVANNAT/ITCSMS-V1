<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentEvalStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $studentstatus = array(
            array(
                'id'=>1,
                'name' => 'pass',
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),array(
                'id'=>2,
                'name' => 'fail',
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),array(
                'id'=>3,
                'name' => 'removed',
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),array(
                'id'=>4,
                'name' => 'exam again',
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )

        );
        foreach($studentstatus as $status){
            DB::table('studentEvalStatuses')->insert($status);
        }
    }
}
