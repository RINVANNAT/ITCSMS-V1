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
        $studentIds = \App\Models\StudentAnnual::where([
            'studentAnnuals.department_id' => 8,
            'studentAnnuals.academic_year_id' => 2018,
            'studentAnnuals.grade_id' => 2,
            'studentAnnuals.degree_id' => 1
        ])
            ->join('group_student_annuals', 'studentAnnuals.id', '=', 'group_student_annuals.student_annual_id')
            ->where('group_student_annuals.semester_id', 2)
            ->whereNull('group_student_annuals.department_id')
            ->pluck('studentAnnuals.student_id');

        foreach ($depts as $dept) {
            array_push($departments, $dept->id);
        }

        $studentAnnuals = \App\Models\StudentAnnual::whereIn('student_id', $studentIds)
            ->select('student_id', 'academic_year_id', 'id', 'grade_id')
            ->orderBy('academic_year_id', 'desc')
            ->get()->toArray();

        $studentAnnuals = collect($studentAnnuals)->groupBy('student_id');

        $data = [];

        foreach ($studentAnnuals as $studentAnnual) {
            $gradeId = null;
            $studentAnnualIds = [];
            foreach ($studentAnnual as $key => $eachStudentAnnual) {
                if ($gradeId == null) {
                    $gradeId = $eachStudentAnnual['grade_id'];
                    array_push($studentAnnualIds, $eachStudentAnnual);
                } else if($gradeId != $eachStudentAnnual['grade_id']) {
                    array_push($studentAnnualIds, $eachStudentAnnual);
                    $gradeId = $eachStudentAnnual['grade_id'];
                }
            }
            array_push($data, $studentAnnualIds);
        }

        foreach ($data as &$studentAnnual) {
            foreach ($studentAnnual as &$item) {
                $score = $this->getStudentOriginScoreBySemester($item['id'], null);
                $item['score'] = $score;
            }
            foreach ($departments as $key => $deptId) {
                $score_1 = null;
                $score_2 = null;

                if ( $studentAnnual[0]['grade_id'] == 1 && (isset($studentAnnual[0]['score']['final_score']))) {
                    $score_1 = $studentAnnual[0]['score']['final_score'];
                }

                if ( $studentAnnual[0]['grade_id'] == 2 && (isset($studentAnnual[0]['score']['final_score']))) {
                    $score_2 = $studentAnnual[0]['score']['final_score'];
                }

                if ( $studentAnnual[1]['grade_id'] == 1 && (isset($studentAnnual[1]['score']['final_score']))) {
                    $score_1 = $studentAnnual[1]['score']['final_score'];
                }

                if ( $studentAnnual[1]['grade_id'] == 2 && (isset($studentAnnual[1]['score']['final_score']))) {
                    $score_2 = $studentAnnual[1]['score']['final_score'];
                }

                \App\Models\DistributionDepartment::create([
                    'academic_year_id' => 2018,
                    'student_annual_id' => $studentAnnual[0]['id'],
                    'department_id' => $deptId,
                    'priority' => $key+1,
                    'score_1' => $score_1,
                    'score_2' => $score_2
                ]);
            }
        }
    }
}
