<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $semesters = array(
            array(
                'id'=>7,
                'name_en' => 'Semester 1',
                'name_fr' => 'Semestre 1',
                'name_kh'=> 'ឆមាសទី១',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>8,
                'name_en' => 'Semester 2',
                'name_fr' => 'Semestre 2',
                'name_kh'=> 'ឆមាសទី២',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($semesters as $semester){
            DB::table('semesters')->insert($semester);
        }
    }
}
