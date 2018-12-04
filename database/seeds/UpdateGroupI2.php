<?php

use Illuminate\Database\Seeder;

class UpdateGroupI2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get id of student I2 academic year 2018-2019
        $studentAnnualIds = \App\Models\StudentAnnual::where('academic_year_id',2019)->where('degree_id',1)->where('grade_id',2)->pluck('id');

        $count = 0;
        // Create group record for I2
        foreach($studentAnnualIds as $id) {
            \App\Models\GroupStudentAnnual::create([
               "student_annual_id" => $id,
               "group_id" => 30,
               "semester_id" => 1,
               "created_at" => \Carbon\Carbon::now()
            ]);
            $count++;
        }
        dd($count);
    }
}
