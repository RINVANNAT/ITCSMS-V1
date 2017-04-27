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
        $resitScores = DB::table('averages')->where('course_annual_id', $courseAnnualId)->get();

        foreach($resitScores as $score) {
            $arrayScore[$score->student_annual_id] = $score;
        }

        return $arrayScore;
    }

    public function compareResitScore($average) {
        if($average->resit_score > $average->average) {
            return $average->resit_score;
        } else {
            return $average->average;
        }
    }

    public function groupByCourseAnnualTrait($courseAnnualId) {

        $groups = DB::table('groups')
            ->whereIn('id', function($queryClass) use ($courseAnnualId) {

                $groupIds = DB::table('course_annual_classes')->where([ ['course_annual_id', $courseAnnualId], ['course_session_id', null] ])->lists('group_id');

                dd($groupIds);
                $queryClass->select( $groupIds );
            })->get();

            dd($groups);

    }

    public function score_constraint( $each_score, $tmp_course,$student, $each_column_score) {

        if($each_score > ScoreEnum::Zero) {//----student with score 0 consider as giving up studying do not count for jurring student average
            $each_column_score[$tmp_course->course_id][$student->id_card] = $each_score;
        }

        return $each_column_score;
    }





}







