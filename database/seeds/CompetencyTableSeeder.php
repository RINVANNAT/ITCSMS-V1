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
                'name' => 'DELF/DALF',
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ]
        ];

        $enProperties = [
            'readOnly' => false,
            'max' => 10,
            'min' => 0,
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
                'name' => 'S',
                'competency_type_id' => 1,
                'properties' => json_encode($enProperties),
                'type' => "value",
                'calculation_rule' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'R',
                'competency_type_id' => 1,
                'properties' => json_encode($enProperties),
                'type' => "value",
                'calculation_rule' => null,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'W',
                'properties' => json_encode($enProperties),
                'type' => "value",
                'calculation_rule' => null,
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'L',
                'properties' => json_encode($enProperties),
                'type' => "value",
                'calculation_rule' => null,
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],

            [
                'name' => 'Overall Band Score',
                'properties' => json_encode(['min' => 9, 'max'=> 40, 'color' => null, 'readOnly' => true]),
                'calculation_rule' => '{"+":[{"var":"s"}, {"var":"r"}, {"var":"w"}, {"var":"l"}]}',
                'type' => "calculation",
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'IELTS Band Score',
                'properties' => json_encode(['min' => 4, 'max'=> 9, 'color' => null, 'readOnly' => true]),
                'calculation_rule' => '{"/":[{"+":[{"var":"s"}, {"var":"r"}, {"var":"w"}, {"var":"l"}]},4]}',
                'type' => "calculation",
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'Level',
                'properties' => json_encode(['min' => null, 'max'=> null, 'color' => null, 'readOnly' => true]),
                'calculation_rule' => '{"if":[{"and":[{">=":[{"var":"l"},5]},{">=":[{"var":"w"},5]},{">=":[{"var":"r"},5]},{">=":[{"var":"s"},5]}]},{"if":[{"<":[{"/":[{"+":[{"var":"s"},{"var":"r"},{"var":"w"},{"var":"l"}]},4]},4.5]},"",{"<":[{"/":[{"+":[{"var":"s"},{"var":"r"},{"var":"w"},{"var":"l"}]},4]},5.5]},"A2",{"<":[{"/":[{"+":[{"var":"s"},{"var":"r"},{"var":"w"},{"var":"l"}]},4]},6.5]},"B1",{"<":[{"/":[{"+":[{"var":"s"},{"var":"r"},{"var":"w"},{"var":"l"}]},4]},7.5]},"B2",{"<":[{"/":[{"+":[{"var":"s"},{"var":"r"},{"var":"w"},{"var":"l"}]},4]},8]},"C1","C2"]},""]}',
                'type' => "condition",
                'competency_type_id' => 1,
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],



            [
                'name' => 'CO',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'calculation_rule' => null,
                'type' => "value",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'CE',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'calculation_rule' => null,
                'type' => "value",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'PO',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'calculation_rule' => null,
                'type' => "value",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'PE',
                'properties' => json_encode($frProperties),
                'competency_type_id' => 2,
                'calculation_rule' => null,
                'type' => "value",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],


            [
                'name' => 'Total Score',
                'properties' => json_encode(['min' => 25, 'max' => 100, 'color' => 'green', 'readOnly' => true]),
                'competency_type_id' => 2,
                'calculation_rule' => '{"+":[{"var":"ce"}, {"var":"co"}, {"var":"pe"}, {"var":"po"}]}',
                'type' => "calculation",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ],
            [
                'name' => 'ADMISSION',
                'properties' => json_encode(['min' => null, 'max' => null, 'color' => null, 'readOnly' => true]),
                'competency_type_id' => 2,
                'calculation_rule' => '{"if":[{"and":[{">=":[{"var":"ce"},5]},{">=":[{"var":"pe"},5]},{">=":[{"var":"co"},5]},{">=":[{"var":"po"},5]},{">=":[{"+":[{"var":"ce"},{"var":"co"},{"var":"pe"},{"var":"po"}]},50]}]},{"if":[{"==":[{"var":"gender"},"M"]},"Admis",{"==":[{"var":"gender"},"F"]},"Admise"]},{"if":[{"==":[{"var":"gender"},"M"]},"Non Admis",{"==":[{"var":"gender"},"F"]},"Non Admise"]}]}',
                'type' => "condition",
                'created_at' => Carbon::now(),
                'create_uid' => 1
            ]
        ];

        $insertCompetencyType = \Illuminate\Support\Facades\DB::table('competency_types')->insert($competency_type);
        if($insertCompetencyType) {
            \Illuminate\Support\Facades\DB::table('competencies')->insert($competencies);
        }

    }
}
