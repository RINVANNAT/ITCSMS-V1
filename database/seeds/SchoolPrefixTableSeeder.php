<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolPrefixTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schoolPrefixs = array(
            array(
                'id'=>1,
                'name_kh' => 'មត្តេយ្យ',
                'name_en' =>'Pre School',
                'code'=>'មត្តេយ្យ.',
                'is_moe'=>false,
            ),
            array(
                'id'=>2,
                'name_kh' => 'បឋមសិក្សា',
                'name_en' =>'Primary School',
                'code'=>'បឋម.',
                'is_moe'=>false,
            ),
            array(
                'id'=>3,
                'name_kh' => 'អនុវិទ្យាល័យ',
                'name_en' =>'Lower Secondary School',
                'code'=>'អនុវិ.',
                'is_moe'=>false,
            ),
            array(
                'id'=>4,
                'name_kh' => 'វិទ្យាល័យ',
                'name_en' =>'Upper Secondary School',
                'code'=>'វិ.',
                'is_moe'=>false,
            ),
            array(
                'id'=>5,
                'name_kh' => 'នាយកដ្ធាន',
                'name_en' =>'Department',
                'code'=>'នា.',
                'is_moe'=>true,
            ),
            array(
                'id'=>6,
                'name_kh' => 'អគ្គនាយកដ្ធាន',
                'name_en' =>'General Directorate',
                'code'=>'អគ្គនា.',
                'is_moe'=>true,
            ),
            array(
                'id'=>7,
                'name_kh' => 'អធិការដ្ធាន',
                'name_en' =>'Inspectorate',
                'code'=>'អធិ.',
                'is_moe'=>true,
            ),
            array(
                'id'=>8,
                'name_kh' => 'អគ្គាធិការដ្ធាន',
                'name_en' =>'General Inspectorate',
                'code'=>'អគ្គាធិ.',
                'is_moe'=>true,
            ),
            array(
                'id'=>9,
                'name_kh' => 'វិទ្យាស្ថាន',
                'name_en' =>'Institute',
                'code'=>'វិ.ស្ថាន',
                'is_moe'=>true,
            ),
            array(
                'id'=>10,
                'name_kh' => 'សាកលវិទ្យាល័យ',
                'name_en' =>'University',
                'code'=>'សាកលវិ.',
                'is_moe'=>true,
            ),
            array(
                'id'=>11,
                'name_kh' => 'សាលា',
                'name_en' =>'General School',
                'code'=>'សាលា.',
                'is_moe'=>true,
            ),
            array(
                'id'=>12,
                'name_kh' => 'អាគត្តដ្ធានផ្សេងៗក្នុងក្រសួងអប់រំ',
                'name_en' =>'',
                'code'=>'',
                'is_moe'=>true,
            ),
            array(
                'id'=>13,
                'name_kh' => 'ស្វ័យបំពេញវិជ្ជា',
                'name_en' =>'',
                'code'=>'',
                'is_moe'=>false,
            ),
        )
        ;
        foreach($schoolPrefixs as $schoolPrefix){
            DB::table('schoolPrefixs')->insert($schoolPrefix);
        }
    }
}
