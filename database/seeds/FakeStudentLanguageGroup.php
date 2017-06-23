<?php

use Illuminate\Database\Seeder;

class FakeStudentLanguageGroup extends Seeder
{
    /**
     * This is just to fake student group for SA/SF department. It is for testing only
     * Run the database seeds.
     * php artisan db:seed --class=FakeStudentLanguageGroup
     * @return void
     */
    public function run()
    {
        $students = \App\Models\StudentAnnual::where('academic_year_id', 2017)
            ->join("students", "students.id", "=", "studentAnnuals.student_id")
            ->where('degree_id', 1)
            ->where('grade_id', 1)
            ->where('department_id', 8)
            ->select("studentAnnuals.*", "students.dob")
            ->orderBy("students.dob", "DESC")
            ->get();
        $i = 1;
        $j = 1;
        $group = \App\Models\Group::where('code', $j)->first();
        foreach ($students as $student) {
            if ($i > 30) {
                $j++;
                $i = 1;
                $group = \App\Models\Group::where('code', $j)->first();
            }
            \Illuminate\Support\Facades\DB::table('group_student_annuals')->insert(
                array(
                    "student_annual_id" => $student->id,
                    "group_id" => $group->id,
                    "semester_id" => 1,
                    "created_at" => \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                    "department_id" => 12
                )
            );
            $i++;
        }

    }
}
