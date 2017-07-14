<?php

use Illuminate\Database\Seeder;

class AdditioalColumnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=AdditioalColumnsSeeder
     * @return void
     */
    public function run()
    {


        $additionalCols = [
            [
               'column_name' => 'Total Score',
                'competency_type_id' => 3,
               'created_at' => \Carbon\Carbon::now()
            ],

            [
                'column_name' => 'ADMISSION',
                'competency_type_id' => 3,
                'created_at' => \Carbon\Carbon::now()
            ],


            [
                'column_name' => 'Overall Band Score',
                'competency_type_id' => 2,
                'created_at' => \Carbon\Carbon::now()
            ],

            [
                'column_name' => 'IELTS Band Score',
                'competency_type_id' => 2,
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'column_name' => 'Level',
                'competency_type_id' => 2,
                'created_at' => \Carbon\Carbon::now()
            ]
        ];

        \Illuminate\Support\Facades\DB::table('additional_columns')->insert($additionalCols);
    }
}
