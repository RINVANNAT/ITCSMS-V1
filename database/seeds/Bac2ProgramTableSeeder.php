<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Bac2ProgramTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bac2Programs = array(
            array(
                'id'=>1,
                'name_kh' => 'វិទ្យាសាស្ត្រ',
            ),
            array(
                'id'=>2,
                'name_kh' => 'វិទ្យាសាស្ត្រសង្គម',
            ),
        )
        ;
        foreach($bac2Programs as $bac2Program){
            DB::table('bac2Programs')->insert($bac2Program);
        }
    }
}
