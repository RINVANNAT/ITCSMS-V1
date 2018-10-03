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
        $groups = \App\Models\Group::all();

        foreach ($groups as $group) {
            if (!is_null($group->code)) {
                (new \App\Models\Schedule\Timetable\TimetableGroup())->create([
                    'name_kh' => $group->name_kh,
                    'name_en' => $group->name_kh,
                    'name_fr' => $group->name_fr,
                    'code' => $group->code,
                    'description' => ''
                ]);
            }
        }
    }
}
