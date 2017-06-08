<?php

use App\Models\Configuration;
use App\Models\Department;
use Illuminate\Database\Seeder;

/**
 * Class TimetableAssignmentSeeder
 * php artisan db:seed --class=TimetableAssignmentSeeder
 */
class TimetableAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = Department::where('parent_id', 11)->get();
        // delete existed record in Configuration table.
        foreach ($departments as $department) {
            $configuration = Configuration::where('key', 'timetable_' . $department->id)->first();
            if ($configuration instanceof Configuration) {
                $configuration->delete();
            }
            // add new record.
            $newConfiguration = new Configuration();
            $newConfiguration->key = 'timetable_' . $department->id;
            $newConfiguration->value = $department->id;
            $newConfiguration->description = 'finished';
            $newConfiguration->created_at = '2017-02-02';
            $newConfiguration->updated_at = '2017-02-04';
            $newConfiguration->save();
        }
    }
}
