<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $histories = array(
            array(
                'id'=>1,
                'name_kh' => 'DUT+0',
                'name_en' => 'DUT+0',
                'name_fr' => 'DUT+0',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2,
                'name_kh' => 'DUT+1',
                'name_en' => 'DUT+1',
                'name_fr' => 'DUT+1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>3,
                'name_kh' => 'DUT+2',
                'name_en' => 'DUT+2',
                'name_fr' => 'DUT+2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>4,
                'name_kh' => 'T1+0',
                'name_en' => 'T1+0',
                'name_fr' => 'T1+0',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>5,
                'name_kh' => 'T1+1',
                'name_en' => 'T1+1',
                'name_fr' => 'T1+1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>6,
                'name_kh' => 'T1+2',
                'name_en' => 'T1+2',
                'name_fr' => 'T1+2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>7,
                'name_kh' => 'T2+0',
                'name_en' => 'T2+0',
                'name_fr' => 'T2+0',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>8,
                'name_kh' => 'T2+1',
                'name_en' => 'T2+1',
                'name_fr' => 'T2+1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>9,
                'name_kh' => 'T2+2',
                'name_en' => 'T2+2',
                'name_fr' => 'T2+2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>10,
                'name_kh' => 'Ex. T1',
                'name_en' => 'Ex. T1',
                'name_fr' => 'Ex. T1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>11,
                'name_kh' => 'Ex. T2',
                'name_en' => 'Ex. T2',
                'name_fr' => 'Ex. T2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>12,
                'name_kh' => 'Ex. T3',
                'name_en' => 'Ex. T3',
                'name_fr' => 'Ex. T3',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>13,
                'name_kh' => 'Ex. T3+0',
                'name_en' => 'Ex. T3+0',
                'name_fr' => 'Ex. T3+0',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>14,
                'name_kh' => 'Laos',
                'name_en' => 'Laos',
                'name_fr' => 'Laos',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>15,
                'name_kh' => 'Vietnam',
                'name_en' => 'Vietnam',
                'name_fr' => 'Vietnam',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>16,
                'name_kh' => 'Other',
                'name_en' => 'Other',
                'name_fr' => 'Other',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($histories as $history){
            DB::table('histories')->insert($history);
        }
    }
}
