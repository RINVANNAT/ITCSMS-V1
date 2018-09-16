<?php

use Illuminate\Database\Seeder;

class DistributionDepartmentResults extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentAnnualIds = \App\Models\StudentAnnual::where([
            'academic_year_id' => 2018,
            'degree_id' => 1,
            'grade_id' => 2
        ])->pluck('id');

        $studentAnnualDistributionDepartments = \App\Models\DistributionDepartment::whereIn('student_annual_id', $studentAnnualIds)
            ->select('student_annual_id', 'score')
            ->distinct('student_annual_id')
            ->orderBy('score', 'desc')
            ->get();

        $departments = [];
        $depts = \App\Models\Department::where('is_specialist', true)->get();
        foreach ($depts as $key =>  $dept) {
            $total = 82;
            if ($key== 0) {
                $total = 91;
            }
            array_push($departments, ['id' => $dept->id, 'total' => $total]);
        }

        foreach ($studentAnnualDistributionDepartments as $annualDistributionDepartment) {
            $data = \App\Models\DistributionDepartment::where('student_annual_id', $annualDistributionDepartment->student_annual_id)
                ->select('id', 'student_annual_id', 'score', 'department_id', 'priority')
                ->orderBy('priority', 'asc')
                ->get()->toArray();

            $departmentIdSelected = null;
            $departmentOptionIdSelected = null;
            $prioritySelected = null;
            $isBreak = false;
            $student_annual_id = null;

            foreach ($data as $item) {
                $score = $item['score'];
                $student_annual_id = $item['student_annual_id'];
                foreach ($departments as &$department) {
                    if ($item['department_id'] == $department['id']) {
                        if ($department['total'] > 0) {
                            $department['total']--;
                            $departmentIdSelected = $department['id'];
                            $prioritySelected = $item['priority'];
                            $isBreak = true;
                            break;
                        }
                    }
                }
                if ($isBreak) {
                    $result = \App\Models\DistributionDepartmentResult::where('student_annual_id', $student_annual_id)->first();
                    if ($result instanceof \App\Models\DistributionDepartmentResult) {
                        $result->update([
                            'student_annual_id' => $student_annual_id,
                            'department_id' => $departmentIdSelected,
                            'department_option_id' => $departmentOptionIdSelected,
                            'total_score' => $score,
                            'priority' => $prioritySelected
                        ]);
                    }else {
                        \App\Models\DistributionDepartmentResult::create([
                            'student_annual_id' => $student_annual_id,
                            'department_id' => $departmentIdSelected,
                            'department_option_id' => $departmentOptionIdSelected,
                            'total_score' => $score,
                            'priority' => $prioritySelected
                        ]);
                    }
                    break;
                }
            }
        }
        dump($departments);
    }
}
