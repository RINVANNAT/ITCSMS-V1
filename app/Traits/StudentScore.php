<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait StudentScore {

    private function floatFormat($val) {

        return number_format((float)$val, 2, '.', '');
    }

    private function averagePropertiesFromDB($array_course_annual_ids) {
        $arrayAverage = [];
        $arrayScores=[];
        $averageProperties = DB::table('averages')
            ->whereIn('course_annual_id', $array_course_annual_ids)
            ->select('average', 'course_annual_id', 'student_annual_id', 'description')
            ->orderBy('student_annual_id')->get();

        foreach($averageProperties as $average) {
            $arrayAverage[$average->course_annual_id][$average->student_annual_id]= $average;
            $arrayScores[$average->course_annual_id][] = $average->average;
        }

        return [
            'average_score' => $arrayScores,
            'average_object'=> $arrayAverage
        ];
    }
    private function absencePropFromDB($array_course_annual_ids) {

        $arrayAbsence=[];
        $absenceProperties = DB::table('absences')->whereIn('course_annual_id', $array_course_annual_ids)->get();
        foreach($absenceProperties as $absence) {
            $arrayAbsence[$absence->course_annual_id][$absence->student_annual_id]= $absence;
        }

        return $arrayAbsence;
    }

    public function getCourseAnnualWithScore($array_course_annual_ids) {// ---$courseAnnually---collections of all courses by dept, grade, semester ...

        $averageProps = $this->averagePropertiesFromDB($array_course_annual_ids);;
        $averageScore =  $averageProps['average_score'];
        $averageObject = $averageProps['average_object'];
        $absences = $this->absencePropFromDB($array_course_annual_ids);
        return ['averages'=>$averageObject,'absences'=>$absences, 'arrayCourseScore'=>$averageScore] ;
    }

    public function getCourseAnnually() {

        $courseAnnuals = DB::table('course_annuals')
            ->select(
                'course_annuals.name_kh',
                'course_annuals.name_en',
                'course_annuals.name_fr',
                'course_annuals.id as course_annual_id',
                'course_annuals.course_id as course_id',
                'course_annuals.department_id',
                'course_annuals.degree_id',
                'course_annuals.grade_id',
                'course_annuals.time_tp',
                'course_annuals.time_td',
                'course_annuals.time_course',
                'course_annuals.semester_id',
                'course_annuals.employee_id',
                'course_annuals.active',
                'course_annuals.academic_year_id',
                'course_annuals.credit as course_annual_credit',
                'course_annuals.is_counted_creditability',
                'course_annuals.is_counted_absence',
                'course_annuals.department_option_id'
            );

        return $courseAnnuals;
    }

    public function getStudentScoreBySemester($studentAnnualId, $semester_id) {


        $student = [];
        $courseAnnualByProgram = [];
        $arrayCourseAnnualIds = [];
        $classByCourseAnnualIds = [];


        if($studentAnnualId){
            $studentAnnual = DB::table('studentAnnuals')->where('id', $studentAnnualId)->first();
            $semesters = DB::table('semesters')->get();

            $courseAnnuals = $this->getCourseAnnually();

            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', $studentAnnual->department_id);
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', $studentAnnual->academic_year_id);
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', $studentAnnual->degree_id);
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', $studentAnnual->grade_id);
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', $studentAnnual->department_option_id);

            if($semester_id) {
                $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', $semester_id);
            }

            $courseAnnuals = $courseAnnuals->get();

            if($courseAnnuals) {

                foreach($courseAnnuals as $courseAnnual) {
                    $arrayCourseAnnualIds[] = $courseAnnual->course_annual_id;
                    $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual;
                }

                $eachScoreCourseAnnual = $this->getCourseAnnualWithScore($arrayCourseAnnualIds);
                $averages = $eachScoreCourseAnnual['averages'];
                $absences = $eachScoreCourseAnnual['absences'];

                $courseAnnualClass = DB::table('course_annual_classes')->whereIn('course_annual_id', $arrayCourseAnnualIds)->get();

                foreach($courseAnnualClass as $class) {
                    $classByCourseAnnualIds[$class->course_annual_id][]= $class->group;
                }

                foreach($courseAnnuals as $courseAnnual) {

                    $groups = isset($classByCourseAnnualIds[$courseAnnual->course_annual_id])?$classByCourseAnnualIds[$courseAnnual->course_annual_id]:[];
                    $class = [];
                    foreach($groups as $group) {
                        if($group != null) {
                            $class[] = $group;
                        }
                    }
                    if(count($class) > 0) {
                        if(in_array($studentAnnual->group, $groups)) {

                            if(isset($absences[$courseAnnual->course_annual_id][$studentAnnual->id])){
                                $absence = $absences[$courseAnnual->course_annual_id][$studentAnnual->id]->num_absence;
                            } else {
                                $absence = 0;
                            }

                            //---this is the course annual which this student learn
                            $student[$studentAnnual->id][$courseAnnual->course_annual_id] = [

                                'name_kh' => $courseAnnual->name_kh,
                                'name_en' => $courseAnnual->name_en,
                                'name_fr' => $courseAnnual->name_fr,
                                'credit'  => $courseAnnual->course_annual_credit,
                                'semester' => $courseAnnual->semester_id,
                                'absence' => $absence,
                                'score'    => isset($averages[$courseAnnual->course_annual_id])?$averages[$courseAnnual->course_annual_id][$studentAnnual->id]->average:null
                            ];
                        }
                    } else {

                        if(isset($absences[$courseAnnual->course_annual_id][$studentAnnual->id])){
                            $absence = $absences[$courseAnnual->course_annual_id][$studentAnnual->id]->num_absence;
                        } else {
                            $absence = 0;
                        }

                        $student[$studentAnnual->id][$courseAnnual->course_annual_id] = [

                            'name_kh' => $courseAnnual->name_kh,
                            'name_en' => $courseAnnual->name_en,
                            'name_fr' => $courseAnnual->name_fr,
                            'credit'  => $courseAnnual->course_annual_credit,
                            'semester' => $courseAnnual->semester_id,
                            'absence' => $absence,
                            'score'    => isset($averages[$courseAnnual->course_annual_id])?$averages[$courseAnnual->course_annual_id][$studentAnnual->id]->average:null
                        ];
                    }
                }


                $subjects = $student[$studentAnnualId];
                $totalCredit = 0;
                $score = 0;

                $totalCredit_s1 = 0;
                $score_s1 = 0;
                $totalCredit_s2 = 0;
                $score_s2 = 0;

                foreach ($subjects as $course_annual_id => $subject) {
                    $totalCredit = $totalCredit + $subject['credit'];
                    $score = $score + ($subject['credit'] * $subject['score']);

                    if($subject["semester"] == 1){
                        $totalCredit_s1 = $totalCredit_s1 + $subject['credit'];
                        $score_s1 = $score_s1 + ($subject['credit'] * $subject['score']);
                    } else {
                        $totalCredit_s2 = $totalCredit_s2 + $subject['credit'];
                        $score_s2 = $score_s2 + ($subject['credit'] * $subject['score']);
                    }
                }

                if($totalCredit != 0){
                    $moyenne = $this->floatFormat(($score/$totalCredit));
                } else {
                    $moyenne = 0;
                }

                if($totalCredit_s1 != 0){
                    $moyenne_s1 = $this->floatFormat(($score_s1/$totalCredit_s1));
                } else {
                    $moyenne_s1 = 0;
                }

                if($totalCredit_s2 != 0){
                    $moyenne_s2 = $this->floatFormat(($score_s2/$totalCredit_s2));
                } else {
                    $moyenne_s2 = 0;
                }

                $student = array_merge($subjects, ['final_score' => $moyenne]);
                $student = array_merge($student, ['final_score_s1' => $moyenne_s1]);
                $student = array_merge($student, ['final_score_s2' => $moyenne_s2]);


            } else {
                $student = [];
            }

            return $student;
        } else {
            return null;
        }

    }
}