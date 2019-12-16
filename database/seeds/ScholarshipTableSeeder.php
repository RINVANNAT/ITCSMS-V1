<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scholarships = array(
            array(
                'code' => 'Boursier Partielle',
                'isDroppedUponFail'=>false,
                'duration'=>'Full',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier M',
                'isDroppedUponFail'=>true,
                'duration'=>'Full',
                'founder'=>'Ministry of Education and Sport',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier L',
                'isDroppedUponFail'=>true,
                'duration'=>'Full',
                'founder'=>'Ministry of Education and Sport',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier P',
                'isDroppedUponFail'=>true,
                'duration'=>'Full',
                'founder'=>'Ministry of Education and Sport',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier F',
                'isDroppedUponFail'=>true,
                'duration'=>'Full',
                'founder'=>'Ministry of Education and Sport',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier AUF',
                'isDroppedUponFail'=>true,
                'duration'=>'Full',
                'founder'=>'Agence Universitaire de la Francophonie',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'name_fr'=>'Boursier Banque Mondiale',
                'code' => 'Boursier WB',
                'isDroppedUponFail'=> false,
                'duration'=>'5 years',
                'founder'=>'Banque Mondiale',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier ITC',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'MoWRAM',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'founder'=>'Ministry of water resources and meteorology',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Sumitomo',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'D.K.Kim',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'founder'=>'D.K. Kim Foundation',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'AFS',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Akaraka',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'CDRI',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Enfants du Mekong',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Harpswell Foundation',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Total Cambodge',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'CEEF',
                'isDroppedUponFail'=> true,
                'duration'=>'1 year',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier ITC 50%',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier P ITC 70%',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier P ITC 100%',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier P ITC 50%',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
            array(
                'code' => 'Boursier P ITC PK',
                'isDroppedUponFail'=> true,
                'duration'=>'4 years',
                'founder'=>'Institute of Technology of Cambodia អាហារូបករណ៍ អាទិភាព ITC',
                'create_uid' => 1,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ),
        );
        foreach ($scholarships as $scholarship) {
            DB::table('scholarships')->insert($scholarship);
        }
    }
}
