<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GdeGradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gdeGrades = array(
            array(
                'id'=>34,
                'name_kh' => 'ល្ហណាស់',
                'name_en' => 'A',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>35,
                'name_kh' => 'ល្ង',
                'name_en' => 'B',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>36,
                'name_kh' => 'ល្ហបង្គួរ',
                'name_en' => 'C',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>37,
                'name_kh' => 'មធ្យម',
                'name_en' => 'D',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>38,
                'name_kh' => 'ខ្សោយ',
                'name_en' => 'E',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>39,
                'name_kh' => 'ធ្លាក់',
                'name_en' => 'F',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($gdeGrades as $gdeGrade){
            DB::table('gdeGrades')->insert($gdeGrade);
        }
    }
}
