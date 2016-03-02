<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentWorkTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentWorkTypes = array(
            array(
                'id'=>79,
                'name_en' => 'Before Study',
                'name_kh'=> 'មុនពេលចូលរៀន',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>80,
                'name_en' => 'Studying',
                'name_kh'=> 'កំពុងសិក្សា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>81,
                'name_en' => 'Graduation',
                'name_kh'=> 'ក្រោយរៀនចប់',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>82,
                'name_en' => 'Not Yet',
                'name_kh'=> 'មិនទាន់មាន',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($studentWorkTypes as $studentWorkType){
            DB::table('studentWorkTypes')->insert($studentWorkType);
        }
    }
}
