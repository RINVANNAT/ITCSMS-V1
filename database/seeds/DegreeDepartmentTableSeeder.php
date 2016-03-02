<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'id'=>config('access.departments.department_gca'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_gci'),

                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_gee'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_gic'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_gim'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_ggg'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_gru'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer'),
                    config('access.degrees.degree_associate'),
                    config('access.degrees.degree_master'),
                )
            ),
            array(
                'id'=>config('access.departments.department_tc'),
                'degrees'=>array(
                    config('access.degrees.degree_engineer')
                )
            ),
        );

        $degree_departments = array();

        foreach($data as $department){
            foreach($department['degrees'] as $degree){
                array_push($degree_departments,array(
                    'department_id' => $department['id'],
                    'degree_id' => $degree
                ));
            }
        }

        foreach($degree_departments as $degree_department){
            DB::table('degree_department')->insert($degree_department);
        }
    }
}
