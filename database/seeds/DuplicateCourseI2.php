<?php

use Illuminate\Database\Seeder;
use App\Models\DepartmentOption;
use App\Models\CourseAnnual;
use Illuminate\Support\Facades\DB;
use App\Models\Score;
use App\Models\StudentAnnual;
use App\Models\PercentageScore;
use App\Models\Percentage;
use App\Models\Absence;

class DuplicateCourseI2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function getNewCourseId($courseDictionary, $oldCourseId, $student) {
        $courseDictionaryByOldId = $courseDictionary[$oldCourseId];
        foreach($courseDictionaryByOldId as $a) {
            if($a["option_id"] == $student->department_option_id){
                return $a["new_id"];
            }
        }
    }

    public function run()
    {
        $departmentOptions = DepartmentOption::where('department_id', 8)
            ->pluck('id');
        $courseAnnuals = CourseAnnual::where([
            'academic_year_id' => 2019,
            'department_id' => 8,
            'degree_id' => 1,
            'grade_id' => 2,
            'semester_id' => 1,
            'department_option_id' => null
        ])->get();

        $studentAnnuals = StudentAnnual::where([
            'academic_year_id' => 2019,
            'department_id' => 8,
            'degree_id' => 1,
            'grade_id' => 2
        ])->get()->keyBy('id');

        $courseDictionary = [];
        foreach($courseAnnuals as $courseAnnual) {
            foreach ($departmentOptions as $departmentOptionId) {
                // duplicate course
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
                array_push(
                    $courseDictionary,
                    array(
                        "old_id" => $courseAnnual->id,
                        "new_id" => $newCourseAnnual->id,
                        "option_id" => $newCourseAnnual->department_option_id
                    )
                );
            }
        }

        $courseDictionary = collect($courseDictionary)->groupBy('old_id');

        $scores = Score::whereIN("course_annual_id",$courseAnnuals->pluck('id'))->get();
        // Update scores base on new course id
        foreach($scores as $score) {
            $score->course_annual_id = $this->getNewCourseId($courseDictionary, $score->course_annual_id,$studentAnnuals[$score->student_annual_id]);
            $score->save();
        }

        $absences = Absence::whereIN("course_annual_id",$courseAnnuals->pluck('id'))->get();
        foreach($absences as $absence) {
            $absence->course_annual_id = $this->getNewCourseId($courseDictionary, $absence->course_annual_id,$studentAnnuals[$absence->student_annual_id]);
            $absence->save();
        }

        $tmpCourseAnnuals = \App\Models\CourseAnnualClass::whereIn('course_annual_id', $courseAnnuals->pluck('id'))->get();
        foreach ($tmpCourseAnnuals as $tmpCourseAnnual) {
            $tmpCourseAnnual->delete();
        }
        foreach($courseAnnuals as $courseAnnual) {
            $courseAnnual->delete();
        }
    }
}