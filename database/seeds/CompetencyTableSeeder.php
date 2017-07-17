<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompetencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CompetencyTableSeeder
     * @return void
     */
    public function run()
    {

        $competency_type = [
            [
                'name' => 'IELTS',
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'DELP',
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ]
        ];

        $enProperties = [
            'readOnly' => false,
            'max' => 40,
            'min' => 10,
            'color' => 'yellow'
        ];

        $frProperties = [
            'readOnly' => false,
            'max' => 25,
            'min' => 5,
            'color' => 'yellow'
        ];

        $competencies  = [

            [
                'name' => 'SP',
                'competency_type_id' => 1,
                'properties' => json_encode(['min' => 9, 'max'=> 50, 'color' => 'yellow', 'readOnly' => false]),
                'rule' => null,
                'is_competency' => true,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'RD',
                'competency_type_id' => 1,
                'properties' => json_encode($enProperties),
                'rule' => null,
                'is_competency' => true,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'WR',
                'properties' => json_encode($enProperties),
                'rule' => null,
                'is_competency' => true,
                'competency_type_id' => 1,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'LS',
                'properties' => json_encode($enProperties),
                'rule' => null,
                'is_competency' => true,
                'competency_type_id' => 1,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],

            [
                'name' => 'Overall Band Score',
                'properties' => json_encode(['min' => 9, 'max'=> 40, 'color' => null, 'readOnly' => true]),
                'rule' => '((ls/4)+(rd/4)+(sp/5)+(wr/4))',
                'is_competency' => false,
                'competency_type_id' => 1,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'IELTS Band Score',
                'properties' => json_encode(['min' => 4, 'max'=> 9, 'color' => null, 'readOnly' => true]),
                'rule' => '(9*(ls+rd+sp+wr))/40',
                'is_competency' => false,
                'competency_type_id' => 1,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'Level',
                'properties' => json_encode(['min' => null, 'max'=> null, 'color' => null, 'readOnly' => true]),
                'rule' => '((ls/4)+(rd/4)+(sp/5)+(wr/4))',
                'is_competency' => false,
                'base_level_type' => json_encode([
                    ['min' => 35, 'max'=> 40, 'color' => 'green', 'readOnly' => true, 'value' => 'Advance'],
                    ['min' => 30, 'max'=> 35, 'color' => '#d1fff7', 'readOnly' => true, 'value' => 'Upper-Level'],
                    ['min' => 25, 'max'=> 30, 'color' => '#a4f9f1', 'readOnly' => true, 'value' => 'InterMediat-Level'],
                    ['min' => 20, 'max'=> 25, 'color' => '#f9f2a4', 'readOnly' => true, 'value' => 'Pre-Level'],
                    ['min' => 15, 'max'=> 20, 'color' => '#f9f2a4', 'readOnly' => true, 'value' => 'Element-Level'],
                    ['min' => 10, 'max'=> 15, 'color' => '#f9f2a4', 'readOnly' => true, 'value' => 'Beginner-Level'],
                    ['min' => 0, 'max'=> 10, 'color' => '#ffe1d1', 'readOnly' => true, 'value' => 'Starter-Level'],

                ]),
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],



            [
                'name' => 'CO',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'rule' => null,
                'is_competency' => false,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'CE',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'rule' => null,
                'is_competency' => false,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'PO',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'rule' => null,
                'is_competency' => false,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'PE',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'rule' => null,
                'is_competency' => false,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],


            [
                'name' => 'Total Score',
                'properties' => json_encode(['min' => 25, 'max' => 100, 'color' => 'green', 'readOnly' => true]),
                'competency_type_id' => 2,
                'rule' => '(ce+co+pe+po)',
                'is_competency' => false,
                'base_level_type' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'ADMISSION',
                'properties' => json_encode(['min' => null, 'max' => null, 'color' => null, 'readOnly' => true]),
                'competency_type_id' => 2,
                'rule' => '(ce+co+pe+po)',
                'base_level_type' => json_encode([
                    ['min' => 25, 'max'=> 40, 'color' => 'green', 'readOnly' => true, 'value' => 'ADMIS', 'gender' => 'M'],
                    ['min' => 25, 'max'=> 40, 'color' => 'green', 'readOnly' => true, 'value' => 'ADMISE', 'gender' => 'F'],
                ]),
                'is_competency' => false,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ]
        ];

        //\Illuminate\Support\Facades\DB::table('competency_types')->insert($competency_type);
         \Illuminate\Support\Facades\DB::table('competencies')->insert($competencies);
    }
}
