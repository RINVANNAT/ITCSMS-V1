<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedoubleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $redoubles = array(
            array(
                'name_kh' => 'Red. I1',
                'name_en' => 'Red. I1',
                'name_fr' => 'Red. I1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. I2',
                'name_en' => 'Red. I2',
                'name_fr' => 'Red. I2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. I3',
                'name_en' => 'Red. I3',
                'name_fr' => 'Red. I3',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. I4',
                'name_en' => 'Red. I4',
                'name_fr' => 'Red. I4',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. I5',
                'name_en' => 'Red. I5',
                'name_fr' => 'Red. I5',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. T1',
                'name_en' => 'Red. T1',
                'name_fr' => 'Red. T1',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. T2',
                'name_en' => 'Red. T2',
                'name_fr' => 'Red. T2',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_kh' => 'Red. T3',
                'name_en' => 'Red. T3',
                'name_fr' => 'Red. T3',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($redoubles as $redouble){
            DB::table('redoubles')->insert($redouble);
        }
    }
}
