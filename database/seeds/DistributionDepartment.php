<?php

use Illuminate\Database\Seeder;

class DistributionDepartment extends Seeder
{
    use \App\Traits\StudentScore;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [];
        $depts = \App\Models\Department::where('is_specialist', true)->get();
        $studentAnnuals = \App\Models\StudentAnnual::where([
            'academic_year_id' => 2018,
            'degree_id' => 1,
            'grade_id' => 2
        ])->get();

        foreach ($depts as $dept) {
            array_push($departments, $dept->id);
        }
        foreach ($studentAnnuals as $studentAnnual) {
            $score = $this->getStudentOriginScoreBySemester($studentAnnual->id, 1);
            $score = ($score['final_score_s1'] * 2) + ($score['final_score_s2'] * 3);
            foreach ($departments as $key => $deptId) {
                \App\Models\DistributionDepartment::create([
                    'academic_year_id' => 2018,
                    'student_annual_id' => $studentAnnual->id,
                    'department_id' => $deptId,
                    'priority' => $key+1,
                    'score' => $score
                ]);
            }
        }
    }
}
