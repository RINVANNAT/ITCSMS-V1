<?php
namespace App\Traits;

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

    public function getStudentAnnualByGroupIds(array $groupIds = array(), $semesterId)
    {
        return DB::table('group_student_annuals')
            ->whereIn('group_id', $groupIds)
            ->where('semester_id', $semesterId)
            ->lists('student_annual_id');

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
        if($average->resit_score > $average->average) {
            return $average->resit_score;
        } else {
            return $average->average;
        }
    }


    public function score_constraint( $each_score, $tmp_course,$student, $each_column_score) {

        if($each_score > ScoreEnum::Zero) {//----student with score 0 consider as giving up studying do not count for jurring student average
            $each_column_score[$tmp_course->course_id][$student->id_card] = $each_score;
        }

        return $each_column_score;
    }





}







