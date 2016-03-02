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
                'name_en' => '2010-2011',
                'name_fr' => '2010-2011',
                'name_kh' => '២០១០-២០១១',
                'code' => '2011',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2012,
                'name_en' => '2011-2012',
                'name_fr' => '2011-2012',
                'name_kh' => '២០១១-២០១២',
                'code' => '2012',
                'description'=>'2012',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2013,
                'name_en' => '2012-2013',
                'name_fr' => '2012-2013',
                'name_kh' => '២០១២-២០១៣',
                'code' => '2013',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2014,
                'name_en' => '2013-2014',
                'name_fr' => '2013-2014',
                'name_kh' => '២០១៣-២០១៤',
                'code' => '2014',
                'description'=>'2014',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2015,
                'name_en' => '2014-2015',
                'name_fr' => '2014-2015',
                'name_kh' => '២០១៤-២០១៥',
                'code' => '2015',
                'description'=>'2015',
                'create_uid'=>1,
                'date_start'=>Carbon\Carbon::now(),
                'date_end'=>Carbon\Carbon::now(),
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'id'=>2016,
                'name_en' => '2015-2016',
                'name_fr' => '2015-2016',
                'name_kh' => '២០១៥-២០១៦',
                'code' => '2016',
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
