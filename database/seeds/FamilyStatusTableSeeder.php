<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $familyStatus = array(
            array(
                'id'=>42,
                'name_en' => 'Alive',
                'name_fr' => 'Vivre',
                'name_kh'=> 'នៅរស់',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>43,
                'name_en' => 'Dead',
                'name_fr' => 'Mort',
                'name_kh'=> 'ស្លាប់',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        );

        foreach($familyStatus as $status){
            DB::table('familyStatus')->insert($status);
        }
    }
}
