<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departmentOptions = array(
            array(
                'name_en' => 'Architect',
                'code' => '_Arch',
                'department_id' => 2,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'Civil',
                'code' => '_Civil',
                'department_id' => 2,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'EAT',
                'code' => '_EAT',
                'department_id' => 3,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'EE',
                'code' => '_EE',
                'department_id' => 3,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'IND',
                'code' => '_Ind',
                'department_id' => 5,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'Mechanic',
                'code' => '_Mé',
                'department_id' => 5,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'Geo-Resources & Geo-technical Engineering',
                'code' => 'g',
                'department_id' => 6,
                'degree_id' => 1,
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),

        )
        ;
        foreach($departmentOptions as $departmentOption){
            DB::table('departmentOptions')->insert($departmentOption);
        }
    }
}
