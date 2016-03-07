<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = array(
            array(
                'name_kh' => 'admin',
                'name_latin' => 'admin',
                'birthdate' => Carbon\Carbon::now(),
                'address' => 'Chom Chao, Phnom Penh',
                'email' => 'admin@itc.edu.kh',
                'phone'=>'0886888078',
                'gender_id'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now(),
                'create_uid' => 1,
                'department_id'=>4
            ),
            array(
                'name_kh' => 'chun thavorac',
                'name_latin' => 'chun thavorac',
                'birthdate' => Carbon\Carbon::now(),
                'address' => 'Chom Chao, Phnom Penh',
                'email' => 'thavorac.chun@gmail.com',
                'phone'=>'0886888078',
                'gender_id'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now(),
                'create_uid' => 1,
                'department_id'=>4
            ),
            array(
                'name_kh' => 'chea chheang',
                'name_latin' => 'chea chheang',
                'birthdate' => Carbon\Carbon::now(),
                'address' => 'Chom Chao, Phnom Penh',
                'email' => 'chheang.chea@gmail.com',
                'phone'=>'0886888078',
                'gender_id'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now(),
                'create_uid' => 1,
                'department_id'=>4
            ),
        )
        ;
        foreach($employees as $employee){
            DB::table('employees')->insert($employee);
        }
    }
}
