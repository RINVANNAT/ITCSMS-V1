<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academicYears = array(
            array(
                'id'=>2011,
                'name_latin' => '2010-2011',
                'name_kh' => '២០១០-២០១១',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2012,
                'name_latin' => '2011-2012',
                'name_kh' => '២០១១-២០១២',
                'description'=>'2012',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2013,
                'name_latin' => '2012-2013',
                'name_kh' => '២០១២-២០១៣',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2014,
                'name_latin' => '2013-2014',
                'name_kh' => '២០១៣-២០១៤',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2015,
                'name_latin' => '2014-2015',
                'name_kh' => '២០១៤-២០១៥',
                'description'=>'2015',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2016,
                'name_latin' => '2015-2016',
                'name_kh' => '២០១៥-២០១៦',
                'description'=>'2016',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($academicYears as $academicYear){
            DB::table('academicYears')->insert($academicYear);
        }
    }
}
