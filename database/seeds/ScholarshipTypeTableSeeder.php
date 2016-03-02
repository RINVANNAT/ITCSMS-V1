<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarshipTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarshipTypes = array(
            array(
                'id'=>47,
                'name_en' => 'Private',
                'name_fr' => 'Privé',
                'name_kh'=> 'បង់ថ្លៃ',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>48,
                'name_en' => 'Scholarship',
                'name_fr' => 'Boursier',
                'name_kh'=> 'អាហារូបករណ៍',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>49,
                'name_en' => 'SPS',
                'name_fr' => 'SPS',
                'name_kh'=> 'ម្ចាស់ជំនួយ',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>50,
                'name_en' => 'Others',
                'name_fr' => 'Others',
                'name_kh'=> 'ផ្សេងៗ',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($scholarshipTypes as $scholarshipType){
            DB::table('scholarshipTypes')->insert($scholarshipType);
        }
    }
}
