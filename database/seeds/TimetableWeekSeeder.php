<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class TimetableWeekSeeder
 */
class TimetableWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=TimetableWeekSeeder
     * @return void
     */
    public function run()
    {

        DB::statement('TRUNCATE weeks CASCADE');

        $weeks = [];
        for ($weekItem = 1; $weekItem <= 36; $weekItem++) {

            $week = array(
                'id' => $weekItem,
                'name_en' => 'Week ' . $weekItem,
                'code' => 'week' . $weekItem,
                'created_uid' => 1,
                'updated_uid' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            );

            if ($weekItem <= 18) {
                $week['semester_id'] = 1;
            } else {
                $week['semester_id'] = 2;
            }

            array_push($weeks, $week);
        }

        if (isset($weeks)) {

            foreach ($weeks as $week) {
                DB::table('weeks')->insert($week);
            }

        }
    }
}
