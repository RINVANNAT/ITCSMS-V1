<?php
namespace App\Traits;

use App\Models\CourseAnnual;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Models\Redouble;
use App\Repositories\Backend\ResitStudentAnnual\ResitStudentAnnualRepositoryContract;
use App\Repositories\Backend\ResitStudentAnnual\EloquentResitStudentAnnualRepository;
use App\Models\Semester;
use App\Models\Enum\ScoreEnum;
use App\Models\Course;



trait ScoreProp {


    public function tabScoreElement(array $prop) {
        /*if(is_array($prop)) {
            $element = array(
                'student_annual_id'=>$student->student_annual_id,
                'student_id_card' => $student->id_card,
                'student_name' => strtoupper($student->name_latin),
                'student_gender' => $student->code,
                'absence'          => (string)(($scoreAbsenceByCourse >= 0)?$scoreAbsenceByCourse:10),
                'num_absence'      => isset($scoreAbsence) ? $scoreAbsence->num_absence:null,
                'average'          => $this->floatFormat($totalScore),
                'notation'        => $storeTotalScore->description
            );
        } else {

        }*/

    }


    public function resitScoreFromDB($courseAnnualId) {

        $arrayScore = [];
        $select = [
            'average', 'course_annual_id','student_annual_id',
            'created_at','total_average_id','description','resit_score', 'id'
        ];
        $resitScores = collect(DB::table('averages')->where('course_annual_id', $courseAnnualId)->select($select)->get())->keyBy('student_annual_id')->toArray();
        return $resitScores;
    }

    public function studentScoreCourseAnnually($courseAnnual)
    {
        $scores = $this->scoreAnnualProp($courseAnnual->id);
        $collect = collect($scores)->groupBy('course_annual_id')->toArray();
        $secondCollect = collect($collect[$courseAnnual->id])->groupBy('student_annual_id')->toArray();
        $arrayData[$courseAnnual->id] = $secondCollect;

        return ($arrayData);
    }

    public function getStudentAnnualByGroupIds(array $groupIds = array(), $semesterId, $departmentId)
    {

        $department = Department::where('id', $departmentId)->first();

        if($department->is_vocational) {

            $studentAnnualIds =  DB::table('group_student_annuals')
                ->whereIn('group_id', $groupIds)
                ->where('semester_id', $semesterId)
                ->where('department_id', $department->id)
                ->lists('student_annual_id');

            return ($studentAnnualIds);
        } else {

            return DB::table('group_student_annuals')
                ->whereIn('group_id', $groupIds)
                ->where('semester_id', $semesterId)
                ->whereNULL('department_id')
                ->lists('student_annual_id');
        }


    }

    public function scoreAnnualProp( $courseAnnualId)
    {

        $select = [
            'scores.course_annual_id', 'scores.student_annual_id',
            'scores.score', 'scores.score_absence', 'percentages.name',
            'percentages.percent', 'percentages.id as percentage_id',
            'scores.id as score_id'
        ];

        $percentages = DB::table('scores')
            ->where('scores.course_annual_id', $courseAnnualId)
            ->join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->select($select)
            ->orderBy('percentages.id')
            ->get();

        return $percentages;
    }

    public function averageByCourseAnnual($courseAnnualId)
    {
        $scoreAverages = DB::table('averages')->where('course_annual_id', $courseAnnualId)->get();
        if($scoreAverages) {
            $averages = collect($scoreAverages)->groupBy('course_annual_id')->toArray();
            $arrayAverages[$courseAnnualId] = collect($averages[$courseAnnualId])->keyBy('student_annual_id')->toArray();
            return $arrayAverages;
        } else {
            return [];
        }

    }

    public function propertiesScores($courseAnnualId)
    {
        $scores = $this->scoreProp($courseAnnualId);
        return $scores;
    }


    public function getPropertiesFromScoreTable($courseAnnual)
    {
        $tableScore = $this->scoreAnnualProp($courseAnnual->id);
        return $tableScore;

    }

    public function compareResitScore($average) {
        if(
            strtolower($average->resit_score) != "a" && strtolower($average->resit_score) != "f"
            && strtolower($average->average) != "a" && strtolower($average->average) != "f"
        ){
            if($average->resit_score > $average->average) {
                return $average->resit_score;
            } else {
                return $average->average;
            }
        } else if (
            (strtolower($average->resit_score) == "a" || strtolower($average->resit_score) == "f" ) &&
            (strtolower($average->average) != "a" && strtolower($average->average) != "f")
        ){
            return $average->average;
        } else {
            return $average->resit_score;
        }
    }


    public function score_constraint( $each_score, $tmp_course,$student, $each_column_score) {

        if($each_score > ScoreEnum::Zero) {//----student with score 0 consider as giving up studying do not count for jurring student average
            $each_column_score[$tmp_course->course_id][$student->id_card] = $each_score;
        }

        return $each_column_score;
    }





    /*---not use but keep if needed nextime ------*/
    public function storeTotalScoreEachCourseAnnual($input)
    {

        $courseAnnual = CourseAnnual::where('id', $input['course_annual_id'])->first();
        if ($courseAnnual->is_allow_scoring != "no" || auth()->user()->allow("input-score-without-blocking")) {
            $totalScore = $this->averages->findAverageByCourseIdAndStudentId($input['course_annual_id'], (int)$input['student_annual_id']);// check if total score existe

            if ($totalScore) {

                //update calcuation total score
                $UpdateAverage = $this->averages->update($totalScore->id, $input);
                if ($UpdateAverage) {
                    return $UpdateAverage;
                }

            } else {
                // insert new calculation score
                $storeAverage = $this->averages->create($input); // store total score then return collection-with ID
                if ($storeAverage) {
                    return $storeAverage;
                }
            }
        } else {

            throw new GeneralException("Permission denied.");

        }

    }




}







