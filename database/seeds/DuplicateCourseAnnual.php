<?php

use Illuminate\Database\Seeder;
use App\Models\DepartmentOption;
use App\Models\CourseAnnual;
use Illuminate\Support\Facades\DB;
use App\Models\Score;
use App\Models\StudentAnnual;
use App\Models\PercentageScore;
use App\Models\Percentage;

class DuplicateCourseAnnual extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ## duplicate course annual for TC-I2-2019
        $result = [];
        $departmentOptions = DepartmentOption::where('department_id', 8)
            ->pluck('id');
        $courseAnnuals = CourseAnnual::where([
            'academic_year_id' => 2019,
            'department_id' => 8,
            'degree_id' => 1,
            'grade_id' => 2,
            'semester_id' => 1,
        ])->get();
        DB::beginTransaction();
        try {
            foreach ($courseAnnuals as $courseAnnual) {
                foreach ($departmentOptions as $departmentOptionId) {
                    $newCourseAnnual = CourseAnnual::firstOrCreate([
                        "name" => $courseAnnual->name,
                        "semester_id" => $courseAnnual->semester_id,
                        "active" => $courseAnnual->active,
                        "academic_year_id" => $courseAnnual->academic_year_id,
                        "employee_id" => $courseAnnual->employee_id,
                        "create_uid" => $courseAnnual->create_uid,
                        "write_uid" => $courseAnnual->write_uid,
                        "course_id" => $courseAnnual->course_id,
                        "score_percentage_column_1" => $courseAnnual->score_percentage_column_1,
                        "score_percentage_column_2" => $courseAnnual->score_percentage_column_2,
                        "score_percentage_column_3" => $courseAnnual->score_percentage_column_3,
                        "time_tp" => $courseAnnual->time_tp,
                        "time_td" => $courseAnnual->time_td,
                        "time_course" => $courseAnnual->time_course,
                        "name_kh" => $courseAnnual->name_kh,
                        "name_en" => $courseAnnual->name_en,
                        "name_fr" => $courseAnnual->name_fr,
                        "credit" => $courseAnnual->credit,
                        "department_id" => $courseAnnual->department_id,
                        "degree_id" => $courseAnnual->degree_id,
                        "grade_id" => $courseAnnual->grade_id,
                        "department_option_id" => $departmentOptionId,
                        "responsible_department_id" => $courseAnnual->responsible_department_id,
                        "is_counted_absence" => $courseAnnual->is_counted_absence,
                        "is_counted_creditability" => $courseAnnual->is_counted_creditability,
                        "is_having_resitted" => $courseAnnual->is_having_resitted,
                        "reference_course_id" => $courseAnnual->reference_course_id,
                        "competency_type_id" => $courseAnnual->competency_type_id,
                        "normal_scoring" => $courseAnnual->normal_scoring,
                        "is_allow_scoring" => $courseAnnual->is_allow_scoring,
                    ]);
                    $scores = Score::where([
                        'academic_year_id' => 2019,
                        'department_id' => 8,
                        'degree_id' => 1,
                        'grade_id' => 2,
                        'semester_id' => 1,
                        'course_annual_id' => $courseAnnual->id
                    ])->get();
                    $studentAnnuals = StudentAnnual::where([
                        'academic_year_id' => 2019,
                        'department_id' => 8,
                        'degree_id' => 1,
                        'grade_id' => 2,
                        'department_option_id' => $departmentOptionId
                    ])->get();
                    foreach ($scores as $score) {
                        $newPercentages = [];
                        $percentageScore = PercentageScore::where('score_id', $score->id)->pluck('percentage_id');
                        if ($percentageScore instanceof PercentageScore) {
                            $percentages = Percentage::whereIn('percentage_id', $percentageScore)->get();
                            foreach ($percentages as $percentage) {
                                $newPercentage = $percentage->replicate();
                                $newPercentage->save();
                                array_push($newPercentages, $newPercentage);
                            }
                        }
                        foreach ($studentAnnuals as $studentAnnual) {
                            if ($score->student_annaul_id == $studentAnnual->id) {
                                $newScore = Score::firstOrCreate([
                                    "degree_id" => $score->degree_id,
                                    "grade_id" => $score->grade_id,
                                    "department_id" => $score->department_id,
                                    "academic_year_id" => $score->academic_year_id,
                                    "semester_id" => $score->semester_id,
                                    "course_annual_id" => $newCourseAnnual->id,
                                    "student_annual_id" => $score->student_annual_id,
                                    "create_uid" => $score->create_uid,
                                    "write_uid" => $score->write_uid,
                                    "score" => $score->score,
                                    "score_type" => $score->score_type,
                                    "score_absence" => $score->score_absence,
                                ]);
                                foreach ($newPercentages as $percentage) {
                                    PercentageScore::create([
                                        'percentage_id' => $percentage->id,
                                        'score_id' => $newScore->id
                                    ]);
                                }
                            }
                        }

                    }
                }
            }
        } catch (\Exception $exception) {
            DB::rollback();
            dump($exception->getMessage());
            dd([]);
        }
        DB::commit();
    }
}
