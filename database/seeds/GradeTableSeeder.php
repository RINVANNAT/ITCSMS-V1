<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = array(
            array(
                'id'=>config('access.grades.grade_1'),
                'name_kh' => 'ឆ្នាំទី១',
                'name_en' => 'Year 1',
                'name_fr' => 'Année 1',
                'code'=>'1',
                'description' => 'year 1',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>config('access.grades.grade_2'),
                'name_kh' => 'ឆ្នាំទី២',
                'name_en' => 'Year 2',
                'name_fr' => 'Année 2',
                'code'=>'2',
                'description' => 'year 2',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>config('access.grades.grade_3'),
                'name_kh' => 'ឆ្នាំទី៣',
                'name_en' => 'Year 3',
                'name_fr' => 'Année 3',
                'code'=>'3',
                'description' => 'year 3',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>config('access.grades.grade_4'),
                'name_kh' => 'ឆ្នាំទី៤',
                'name_en' => 'Year 4',
                'name_fr' => 'Année 4',
                'code'=>'4',
                'description' => 'year 4',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>config('access.grades.grade_5'),
                'name_kh' => 'ឆ្នាំទី៥',
                'name_en' => 'Year 5',
                'name_fr' => 'Année 5',
                'code'=>'5',
                'description' => 'year 5',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($grades as $grade){
            DB::table('grades')->insert($grade);
        }
    }
}
