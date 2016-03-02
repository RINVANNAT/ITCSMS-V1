<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = array(
            array(
                'id' => config('access.schools.itc'),
                'name_kh' => 'វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា',
                'name_en' => 'Institute of Technology of Cambodia',
                'name_fr' => 'Institut de Technologie du Cambodge',
                'code' => 'ITC',
                'language'=>'khmer',
                'create_uid' => 1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
        );

        foreach($schools as $school){
            DB::table('schools')->insert($school);
        }
    }
}
