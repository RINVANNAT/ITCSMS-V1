<?php
/**
 * Created by PhpStorm.
 * User: imac-04
 * Date: 4/25/17
 * Time: 2:01 PM
 */

namespace App\Traits;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

trait StudentTrait
{

    public function getStudentByDeptIdGradeIdDegreeId($deptId, $degreeId, $gradeId, $academicYearID) {

        $department = Department::where('id', $deptId)->first();

        $studentAnnual = DB::table('students')
            ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            //->join('redouble_student', 'students.id', '=', 'redouble_student.student_id')
            //->join('redoubles', 'redoubles.id', '=', 'redouble_student.redouble_id')

            ->join('genders', 'genders.id', '=', 'students.gender_id')
            ->whereIn('studentAnnuals.degree_id', $degreeId)
            ->whereIn('studentAnnuals.grade_id', $gradeId)
            ->where('studentAnnuals.academic_year_id', $academicYearID)
            ->select(
                'studentAnnuals.id as student_annual_id',
                'students.name_latin',
                'students.name_kh','students.radie',
                'students.id_card','students.id as student_id',
                'genders.code',
                /*'studentAnnuals.group',
                'studentAnnuals.group_id',*/
                'studentAnnuals.academic_year_id',
                'studentAnnuals.department_id',
                'studentAnnuals.department_option_id',
                'studentAnnuals.degree_id','studentAnnuals.grade_id',
                'students.observation',
                'studentAnnuals.remark',
                'studentAnnuals.general_remark', 'studentAnnuals.history_id'
                //'redoubles.name_en as redouble_name'
            )
            ->orderBy('students.name_latin');

        if(!$department->is_vocational) {
            $studentAnnual = $studentAnnual->whereIn('studentAnnuals.department_id', $deptId);
        }

        return $studentAnnual;
    }


    /*
     * @ $student_id_cards: array of student id cards
     * @ $student_annual_ids: array of student annual ids
     *
     * */

    public function student_properties($student_id_cards, $student_annual_ids, $academicYearId, $objectStudent) {

       /* $students = DB::table('students')
            ->join('studentAnnuals', function ($query) use($student_id_cards, $student_annual_ids) {
                $query->on('studentAnnuals.student_id', '=', 'students.id')
                    ->whereIn('students.id_card', $student_id_cards)
                    ->whereIn('studentAnnuals.id', $student_annual_ids);
            })
            ->select([
                'students.*', 'studentAnnuals.history_id', 'studentAnnuals.id as student_annual_id', 'studentAnnuals.academic_year_id'
            ])
            ->get();*/

        $students = $objectStudent;

        $studentCollection = collect($students);
        $studentIds = $studentCollection->pluck('student_id')->toArray();

        $idCardToAnnualIds = $studentCollection->map(function($item) {
            return [  $item->id_card => $item->student_annual_id];
        })->collapse()->toArray();

        $idCardToStudentIds = $studentCollection->map(function($item) {
            return [  $item->id_card => $item->student_id];
        })->collapse()->toArray();

        $idCardPointToStudent = $studentCollection->map(function($item) {
            return [  $item->id_card => $item];
        })->collapse()->toArray();

        /*--history--*/

        $historyIds = $studentCollection->pluck('history_id')->filter()->all();
        $histories = collect( DB::table('histories')->whereIn('id', $historyIds)->get())->keyBy('id')->toArray();
        $idCardToHistories = $studentCollection->filter(function($item, $key) {
            if($item->history_id != null) {
                return $item;
            }
        })->map(function($item)  use($histories){
            return [$item->id_card => $histories[$item->history_id]];
        })->collapse()->toArray();

        /*--end history---*/


        /*--scholarship info----*/

        $scholarships = DB::table('scholarships')
            ->join('scholarship_student_annual', function($join) use($student_annual_ids){
                $join->on('scholarship_student_annual.scholarship_id', '=', 'scholarships.id')
                    ->whereIn('scholarship_student_annual.student_annual_id',$student_annual_ids);
            })->get();
        $idCardToAnnualIds = collect($idCardToAnnualIds)->flip()->toArray();

        /*$array_scholarships = [];
        foreach($scholarships as $scholarship) {

            if(isset($idCardToAnnualIds[$scholarship->student_annual_id])) {
                $array_scholarships[$idCardToAnnualIds[$scholarship->student_annual_id]][] =$scholarship ;
            }
        }*/

        /*----end scholarship info---*/


        $idCardToScholarships = collect($scholarships)->map(function($item) use($idCardToAnnualIds) {

            if(isset($idCardToAnnualIds[$item->student_annual_id])) {

                if($item->code != 'Boursier Partielle') {
                    return [ $idCardToAnnualIds[$item->student_annual_id ] => $item];
                }
            }

        })->collapse()->toArray();



        /*--redouble info here---*/

        $redoubles =  DB::table('redoubles')
            ->join('redouble_student', function ($query) use($studentIds) {
                $query->on('redouble_student.redouble_id', '=', 'redoubles.id')
                    ->whereIn('redouble_student.student_id', $studentIds);

            })->get();


        $idCardToStudentIds = collect($idCardToStudentIds)->flip()->toArray();

        $idCardToRedoubles = collect($redoubles)->map(function($item) use($idCardToStudentIds, $academicYearId) {
            if(isset($idCardToStudentIds[$item->student_id])) {
                if($item->academic_year_id != $academicYearId) {
                    return [ $idCardToStudentIds[$item->student_id ] => $item];
                }
            }


        })->collapse()->toArray();


        return [
            'scholarship' => $idCardToScholarships,
            'id_card_to_student' => $idCardPointToStudent,
            'history'            => $idCardToHistories,
            'redouble' => $idCardToRedoubles

        ];
    }


    public function student_hisory($student_id_cards, $student_annual_ids, $academic_year_id) {

        $array_student_observation=[];
        $idCardPointToStudent= [];
        $studentRedoubleHistory = [];

        $students = $this->student_properties($student_id_cards, $student_annual_ids);/*--function in the studentTrait --*/


        foreach($students as $student ) {

            $idCardPointToStudent[$student->id_card] = $student;
            $observation_info= '';
            if(isset($array_student_observation[$student->id_card])) {

                if($student->academic_year_id != $academic_year_id) {
                    $studentRedoubleHistory[$student->id_card] = $student->redouble_name;
                    $new_str_info = $student->redouble_name.' '.$array_student_observation[$student->id_card];
                    $array_student_observation[$student->id_card] = $new_str_info;
                }

            } else {

                if($student->academic_year_id != $academic_year_id) {

                    $studentRedoubleHistory[$student->id_card] = $student->redouble_name;
                    $observation_info = $observation_info.' '.$student->redouble_name;
                    $observation_info = $observation_info.' '.$student->history_name;
                    $observation_info = $observation_info.' '.$student->scholarship_name;
                    $array_student_observation[$student->id_card] = $observation_info;
                }
            }
        }

        return [
            'student_observation' => $array_student_observation,
            'id_card_to_student' => $idCardPointToStudent,
            'history'            => $studentRedoubleHistory

        ];

    }


    public function filtering_student_annual($course_annual, $groups) {


        $groupByCourseAnnual = isset($groups[$course_annual->course_annual_id])?$groups[$course_annual->course_annual_id]:null;

        if($deptOptionId = $course_annual->department_option_id) {

            $filtered_students =  $this->getStudentByDeptIdGradeIdDegreeId([$course_annual->department_id], [$course_annual->degree_id], [$course_annual->grade_id], $course_annual->academic_year_id);
            $filtered_students = $filtered_students->whereIn('studentAnnuals.department_option_id', [$deptOptionId]);

            if($groupByCourseAnnual != null) {

                $studentAnnualIds = $this->getStudentAnnualByGroupIds($groupByCourseAnnual, $course_annual->semester_id);

                $filtered_students = $filtered_students->whereIn('studentAnnuals.id', $studentAnnualIds)->get();
            } else {
                $filtered_students =  $filtered_students->get();//->where('studentAnnuals.group', null)->get();
            }

        } else {

            $filtered_students =  $this->getStudentByDeptIdGradeIdDegreeId([$course_annual->department_id], [$course_annual->degree_id], [$course_annual->grade_id], $course_annual->academic_year_id);

            if($groupByCourseAnnual != null) {
                $studentAnnualIds = $this->getStudentAnnualByGroupIds($groupByCourseAnnual, $course_annual->semester_id);
                $filtered_students = $filtered_students->whereIn('studentAnnuals.id', $studentAnnualIds)->get();
            } else {
                $filtered_students =  $filtered_students->get();//->where('studentAnnuals.group', null)->get();
            }
        }

        return $filtered_students;
    }


    public function init_element($stu_dent, $element) {

        $element[$stu_dent->id_card] = [
            'number'  => "",
            'student_id_card' => trim($stu_dent->id_card),
            'student_name' => trim(strtoupper($stu_dent->name_latin)),
            'student_gender' => trim($stu_dent->code),
            'total' => "0",

        ];

        return $element;

    }

    public function element_handsontable_data() {

    }

    public function concate_element_by_semester($each_score, $semester_id, $semesters, $each_course, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs) {

        if($semester_id) {
            if($each_course->is_counted_creditability) {
                $totalMoyenne[$stu_dent->id_card][$semester_id][] = $this->calculateScoreByCredit($each_course->course_annual_credit, $each_score);
            }

            $totalAbs[$stu_dent->id_card][$semester_id][] = isset($absence_by_course)?$absence_by_course->num_absence:0;

        } else {
            foreach($semesters as $semester) {
                if($semester->id == $each_course->semester_id) {
                    if($each_course->is_counted_creditability) {
                        $totalMoyenne[$stu_dent->id_card][$semester->id][] =  $this->calculateScoreByCredit($each_course->course_annual_credit, $each_score);
                    }

                    $totalAbs[$stu_dent->id_card][$semester->id][] = isset($absence_by_course)?$absence_by_course->num_absence:0;

                }
            }
        }

        if(isset($element[$stu_dent->id_card])) {
            $element[$stu_dent->id_card] = $element[$stu_dent->id_card] + ['Abs'.'_'.($each_course->course_id).'_'.$each_course->semester_id =>  isset($absence_by_course)?$absence_by_course->num_absence:"" , 'Credit'.'_'.($each_course->course_id).'_'.$each_course->semester_id => $this->format_number($each_score)];
        } else {
            return false;
        }

        return [
            'element' => $element,
            'total_moyenne' => $totalMoyenne,
            'abs' => $totalAbs
        ];
    }

    public function format_number($val) {

        return number_format((float)$val, 2, '.', '');
    }


    public function add_element_by_semester($each_score, $semester_id, $semesters, $each_course, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs, $array_student_id_card) {


        if($semester_id) {

            if($each_course->is_counted_creditability) {
                $totalMoyenne[$stu_dent->id_card][$semester_id][] =  $this->calculateScoreByCredit($each_course->course_annual_credit, $each_score);
            }

            $totalAbs[$stu_dent->id_card][$semester_id][] = isset($absence_by_course)?$absence_by_course->num_absence:0 ;
            $element[$stu_dent->id_card] = $element[$stu_dent->id_card] + ["S_".$each_course->semester_id => "Total_S_".$each_course->semester_id];

        } else {
            foreach($semesters as $semester) {

                if($each_course->semester_id == $semester->id) {
                    if($each_course->is_counted_creditability) {
                        $totalMoyenne[$stu_dent->id_card][$semester->id][] =  $this->calculateScoreByCredit($each_course->course_annual_credit, $each_score);
                    }
                    $totalAbs[$stu_dent->id_card][$semester->id][] = isset($absence_by_course)?$absence_by_course->num_absence:0 ;
                }
                $element[$stu_dent->id_card] = $element[$stu_dent->id_card] + ["S_".$semester->id => "Total_S_".$semester->id];
            }
        }

        if(isset($element[$stu_dent->id_card])) {
            $array_student_id_card ['id_card'][] =$stu_dent->id_card;
            $array_student_id_card ['student_annual_id'][] =$stu_dent->student_annual_id;
            $array_student_id_card['object_student'][] = $stu_dent;
            $element[$stu_dent->id_card] = $element[$stu_dent->id_card] + ["Abs_".($each_course->course_id)."_".$each_course->semester_id =>  isset($absence_by_course)?$absence_by_course->num_absence:"", "Credit_".($each_course->course_id)."_".$each_course->semester_id => $this->floatFormat($each_score)];
        } else {

            return false;
        }

        return [
            'element' => $element,
            'total_moyenne' => $totalMoyenne,
            'abs' => $totalAbs,
            'student_id_card' => $array_student_id_card
        ];
    }


    public function calculateScoreByCredit($credit, $score_by_course) {

        return $score_by_course * $credit;
    }

    public function empty_data() {

        return [
            'status' => false,
            'element' =>[],
            'absence' => [],
            'moyenne' => [],
            'each_column_score' => [],
            'fail_subject' => []
        ];
    }

    public function find_student_id_card($element, $stu_dent, $each_course, $each_score) {



    }

}