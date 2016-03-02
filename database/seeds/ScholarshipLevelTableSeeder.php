<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarshipLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarshipLevels = array(
            array(
                'id'=>51,
                'name_en' => 'Full 100%',
                'name_kh'=> 'ពេញ ១០០%',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>52,
                'name_en' => 'Not Full 100%',
                'name_kh'=> 'ក្រោម ១០០%',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>53,
                'name_en' => 'None',
                'name_kh'=> 'គ្មាន',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($scholarshipLevels as $scholarshipLevel){
            DB::table('scholarshipLevels')->insert($scholarshipLevel);
        }
    }
}
