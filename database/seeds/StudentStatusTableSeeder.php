<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentstatus = array(
            array(
                'id'=>61,
                'name_en' => 'Studying',
                'name_kh'=> 'កំពុងសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>62,
                'name_en' => 'Suspended',
                'name_kh'=> 'ព្យួរការសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>63,
                'name_en' => 'Stopped',
                'name_kh'=> 'បោះបង់ការសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>64,
                'name_en' => 'Finished',
                'name_kh'=> 'បញ្ចប់ការសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>65,
                'name_en' => 'Terminated',
                'name_kh'=> 'បញ្ឈប់ការសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>78,
                'name_en' => 'Further Education',
                'name_kh'=> 'បន្តការសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($studentstatus as $status){
            DB::table('studentStatus')->insert($status);
        }
    }
}
