<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointers = array(
            array(
                'id'=>75,
                'name_en' => 'King',
                'name_kh'=> 'ព្រះមហាក្សត្រ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>76,
                'name_en' => 'Board',
                'name_kh'=> 'ក្រុមប្រឹក្សាភិបាល',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>77,
                'name_en' => 'Government',
                'name_kh'=> 'រាជរដ្ធាភិបាល',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>109,
                'name_en' => 'Director University',
                'name_kh'=> 'សាកលវិទ្យាធិការ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        );
        foreach($appointers as $appointer){
            DB::table('appointers')->insert($appointer);
        }
    }
}
