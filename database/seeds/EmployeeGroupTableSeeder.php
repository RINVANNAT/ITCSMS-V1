<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee_groups = array(
            array(
                'role_id' => 1,
                'employee_id' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($employee_groups as $employee_group){
            DB::table('employee_role')->insert($employee_group);
        }
    }
}
