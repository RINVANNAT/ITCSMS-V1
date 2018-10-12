<?php

use Illuminate\Database\Seeder;

class TimetableGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Schedule\Timetable\TimetableGroup::truncate();

        $arrayGroups = array_merge(range('A', 'Z'), range(1, 38));

        foreach ($arrayGroups as $group) {
            (new \App\Models\Schedule\Timetable\TimetableGroup())->create([
                'code' => $group
            ]);
        }
    }
}
