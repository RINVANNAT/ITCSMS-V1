<?php

namespace App\Console\Commands;

use App\Models\DistributionDepartment;
use App\Models\StudentAnnual;
use App\Traits\StudentScore;
use Illuminate\Console\Command;

class SetOriginalScoreToDistributionDepartmentConsole extends Command
{
    use StudentScore;

    /**
     * Set original score to distribution department table
     *
     * @var string
     */
    protected $signature = 'smis:set-original-score {academic_year_id}';

    /**
     * Set original score to distribution department table.
     *
     * @var string
     */
    protected $description = 'Set original score to distribution department table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $studentIds = DistributionDepartment::where([
            'distribution_departments.academic_year_id' => $this->argument('academic_year_id')
        ])
            ->join('studentAnnuals', 'studentAnnuals.id', '=', 'distribution_departments.student_annual_id')
            ->distinct('distribution_departments.student_annual_id')
            ->join('group_student_annuals', 'studentAnnuals.id', '=', 'group_student_annuals.student_annual_id')
            ->where('group_student_annuals.semester_id', 2)
            ->whereNull('group_student_annuals.department_id')
            ->pluck('studentAnnuals.student_id');

        $studentAnnuals = StudentAnnual::whereIn('student_id', $studentIds)
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
                } else if ($gradeId != $eachStudentAnnual['grade_id']) {
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
            $score_1 = null;
            $score_2 = null;
            $studentAnnualId = null;
            if ($studentAnnual[0]['grade_id'] == 2) {
                $studentAnnualId = $studentAnnual[0]['id'];
            }
            if ($studentAnnual[1]['grade_id'] == 2) {
                $studentAnnualId = $studentAnnual[1]['id'];
            }

            if ($studentAnnual[0]['grade_id'] == 1 && (isset($studentAnnual[0]['score']['final_score']))) {
                $score_1 = $studentAnnual[0]['score']['final_score'];
            }

            if ($studentAnnual[0]['grade_id'] == 2 && (isset($studentAnnual[0]['score']['final_score']))) {
                $score_2 = $studentAnnual[0]['score']['final_score'];
            }

            if ($studentAnnual[1]['grade_id'] == 1 && (isset($studentAnnual[1]['score']['final_score']))) {
                $score_1 = $studentAnnual[1]['score']['final_score'];
            }

            if ($studentAnnual[1]['grade_id'] == 2 && (isset($studentAnnual[1]['score']['final_score']))) {
                $score_2 = $studentAnnual[1]['score']['final_score'];
            }

            $diss = DistributionDepartment::where([
                'academic_year_id' => $this->argument('academic_year_id'),
                'student_annual_id' => $studentAnnualId
            ])->get();

            foreach ($diss as $eachDis) {
                if ($eachDis instanceof DistributionDepartment) {
                    $eachDis->update([
                        'score_1' => $score_1,
                        'score_2' => $score_2
                    ]);
                }
            }
        }
    }
}
