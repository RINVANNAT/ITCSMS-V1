<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutcomeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $outcomeTypes = array(
            array(
                'code' => '',
                'name' => '',
                'origin' => '',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        );

        foreach($outcomeTypes as $outcomeType){
            DB::table('outcomeTypes')->insert($outcomeType);
        }
    }
}
