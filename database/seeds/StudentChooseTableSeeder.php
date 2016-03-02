<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentChooseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentChooses = array(
            array(
                'id'=>70,
                'name_en' => 'General Education',
                'name_kh'=> 'ចំនេះដឹងទូទៅ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>71,
                'name_en' => 'Equivalence',
                'name_kh'=> 'សមមូល',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>72,
                'name_en' => 'No Information',
                'name_kh'=> 'គ្មានពត៍មាន',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($studentChooses as $studentChoose){
            DB::table('studentChooses')->insert($studentChoose);
        }
    }
}
