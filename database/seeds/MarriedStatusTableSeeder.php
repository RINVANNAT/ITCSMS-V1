<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarriedStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marriedStatus = array(
            array(
                'id'=>24,
                'name_en' => 'Single',
                'name_fr' => 'Célibataire',
                'name_kh'=> 'នៅលីវ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>25,
                'name_en' => 'Married',
                'name_fr' => 'Marrié',
                'name_kh'=> 'រៀបកា',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>26,
                'name_en' => 'Divorced',
                'name_fr' => 'Divorced',
                'name_kh'=> 'លែងលះ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($marriedStatus as $status){
            DB::table('marriedStatus')->insert($status);
        }
    }
}
