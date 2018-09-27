<?php

namespace App\Console\Commands;

use App\Models\DistributionDepartment;
use App\Models\StudentAnnual;
use App\Traits\StudentScore;
use Illuminate\Console\Command;

class DistributionDepartmentGradeIConsole extends Command
{
    use StudentScore;

    /**
     * Set original score to distribution department table
     *
     * @var string
     */
    protected $signature = 'smis:dd-grade-1 {academic_year_id}';

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
            'distribution_departments.academic_year_id' => $this->argument('academic_year_id'),
            'distribution_departments.grade_id' => 1
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

        $studentAnnuals = collect($studentAnnuals)->groupBy('student_id')->toArray();

        foreach ($studentAnnuals as $studentAnnual) {
            $studentAnnualId = 0;
            foreach ($studentAnnual as $eachStudentAnnual) {
                if ($eachStudentAnnual['id'] > $studentAnnualId) {
                    $studentAnnualId = $eachStudentAnnual['id'];
                }
            }

            $score = $this->getStudentOriginScoreBySemester($studentAnnualId, null);
            if (!isset($score['final_score'])) {
                dump($score);
            }
            $diss = DistributionDepartment::where([
                'academic_year_id' => $this->argument('academic_year_id'),
                'grade_id' => 1,
                'student_annual_id' => $studentAnnualId
            ])->get();

            foreach ($diss as $eachDis) {
                if ($eachDis instanceof DistributionDepartment) {
                    $eachDis->update([
                        'score_1' => isset($score['final_score']) ? $score['final_score'] : 0
                    ]);
                }
            }
        }
    }
}
