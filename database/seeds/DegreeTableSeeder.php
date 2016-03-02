<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $degrees = array(
            array(
                'id'=> config('access.degrees.degree_engineer'),
                'name_en' => 'Engineer',
                'name_fr' =>"Ingénieur",
                "name_kh" => "វិស្វករ",
                'code' => 'I',
                'description'=>"I",
                'create_uid' => 1,
                'school_id'=>2,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=> config('access.degrees.degree_associate'),
                'name_en' => 'Associate',
                'name_fr' =>"DUT",
                "name_kh" => "បរិញាប័ត្ររងវិស្វកម្ម",
                'code' => 'T',
                'description'=>"DUT",
                'create_uid' => 1,
                'school_id'=>2,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=> config('access.degrees.degree_bachelor'),
                'name_en' => 'Bachelor',
                'name_fr' =>"Bachelor",
                "name_kh" => "បរិញាប័ត្រ",
                'code' => 'Msc',
                'description'=>"Msc",
                'create_uid' => 1,
                'school_id'=>2,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=> config('access.degrees.degree_master'),
                'name_en' => 'Master',
                'name_fr' =>"Master",
                "name_kh" => "បរិញាប័ត្រជាន់ខ្ពស់",
                'code' => 'Bsc',
                'description'=>"Bsc",
                'create_uid' => 1,
                'school_id'=>2,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=> config('access.degrees.degree_doctor'),
                'name_en' => 'Doctoral',
                'name_fr' =>"Doctoral",
                "name_kh" => "ប​ណ្ឌិត​",
                'code' => 'Phd',
                'description'=>"Phd",
                'create_uid' => 1,
                'school_id'=>2,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($degrees as $degree){
            DB::table('degrees')->insert($degree);
        }
    }
}
