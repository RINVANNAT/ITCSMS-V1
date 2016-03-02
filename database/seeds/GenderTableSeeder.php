<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = array(
            array(
                'id'=>1,
                'name_kh' => 'ប្រុស',
                'name_en' => 'Male',
                'name_fr'=>'Homme',
                'code'=>'M',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2,
                'name_kh' => 'ស្រី',
                'name_en' => 'Female',
                'name_fr'=>'Femme',
                'code'=>'F',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        )
        ;
        foreach($genders as $gender){
            DB::table('genders')->insert($gender);
        }
    }
}
