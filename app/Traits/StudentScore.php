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


trait StudentScore {


    protected $resits;

    public function __construct(ResitStudentAnnualRepositoryContract $resitRepo)
    {

        $this->resits = $resitRepo;

    }

    public function floatFormat($val) {

        return number_format((float)$val, 2, '.', '');
    }
//2040
    public function arrayIdsOfDeptGradeDegreeDeptOption($courseAnnual)
    {

        $groups = $courseAnnual->courseAnnualClass()->select('group_id')
            ->orderBy('group_id')
            ->whereNotNull('group_id')
            ->lists('group_id')->toArray();

        $department_ids = [$courseAnnual->department_id];
        $grade_ids = [$courseAnnual->grade_id];
        $degree_ids = [$courseAnnual->degree_id];
        $departmentOptionIds = [$courseAnnual->department_option_id];
//        $departmentOptionIds = ($courseAnnual->department_option_id !=null)?[$courseAnnual->department_option_id]:null;

        $department_option_ids = [];
        foreach ($departmentOptionIds as $optionId) {
            if ($optionId != null) {
                $department_option_ids[] = $optionId;
            }
        }
        return [
            'department_id' => $department_ids,
            'grade_id' => $grade_ids,
            'degree_id' => $degree_ids,
            'department_option_id' => $department_option_ids,
            'group' => $groups
        ];
    }

    private function averagePropertiesFromDB($array_course_annual_ids) {

        $arrayAverage = [];
        $arrayScores=[];
        $averageProperties = DB::table('averages')
            ->whereIn('course_annual_id', $array_course_annual_ids)
            ->select('average', 'course_annual_id', 'student_annual_id', 'description', 'resit_score')
            ->orderBy('student_annual_id')->get();


        $collection = collect($averageProperties)->groupBy('course_annual_id')->toArray();

        foreach($collection as $key  => $collect) {
            $arrayAverage[$key] = collect($collect)->keyBy('student_annual_id')->toArray();
        }
        return [

            'average_object'=> $arrayAverage
        ];
    }
    private function absencePropFromDB($array_course_annual_ids) {

        $arrayAbsence=[];
        $absenceProperties = DB::table('absences')->whereIn('course_annual_id', $array_course_annual_ids)->get();
        $collection = collect($absenceProperties)->groupBy('course_annual_id')->toArray();

        foreach($collection as $key => $collect) {
            $arrayAbsence[$key]= collect($collect)->keyBy('student_annual_id')->toArray();
        }

        return $arrayAbsence;
    }

    public function getCourseAnnualWithScore($array_course_annual_ids) {// ---$courseAnnually---collections of all courses by dept, grade, semester ...

        $averageProps = $this->averagePropertiesFromDB($array_course_annual_ids);
        $averageObject = $averageProps['average_object'];
        $absences = $this->absencePropFromDB($array_course_annual_ids);
        return ['averages'=>$averageObject,'absences'=>$absences] ;
    }

    public function calculateMoyenneInBothSemster() {

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
    public function findRecordRedouble($student, $redouble, $academicYearId) {

        $redouble_name_history = '';
        $redouble_name_this_year = '';

        if($student->radie == true) {

            return [
                'current_redouble' =>'radié',
                'history_redouble' => ''
            ];
        } else {

            if($redouble) {

                foreach($redouble as $double) {

                    if($double->academic_year_id != $academicYearId) {
                        $redouble_name_history .= $double->name_en;
                    }

                    if($double->academic_year_id == $academicYearId) {
                        if($double->is_changed == true) {
                            $redouble_name_this_year .=$double->name_en;
                        }
                    }
                }

                return [
                    'current_redouble' => $redouble_name_this_year,
                    'history_redouble' => $redouble_name_history
                ];

            }

        }
    }

    public function studentEliminationManager($idCardPointToStudent, $studentRedoubleHistory, $studentIdCard, $check_redouble, $gradeId, $academicYearId, $degreeName) {

        if(isset($studentRedoubleHistory[$studentIdCard])) {//----if student previous academic has had redouble record

            if($check_redouble) {//----/*--if we already set him/her as redouble automaticlly----*/
                if($check_redouble->is_changed) {
                    return '';/*---student pass---*/
                } else {

                    $this->updateStatusStudent($studentIdCard, $status = true); //----if student fail two time in his 5 year academic ---he must be eliminate from school
                    return  'Radié';
                }
            } else {

                $this->updateStatusStudent($studentIdCard, $status = true); //----if student fail two time in his 5 year academic ---he must be eliminate from school
                $this->createRedoubleRecord($idCardPointToStudent[$studentIdCard], $redName = trim($degreeName.$gradeId), $academicYearId, $isChanged = false);
                return  'Radié';/*---we dont have to store the redouble of this year because the jurry consider to eliminate this student from the system (set status active=false)*/
            }

        } else {

            if($check_redouble) { /*--if we already set him/her as redouble automaticlly----*/
                /*--if the record exist we dont create more but we have to check if he/she were changed----*/

                if($check_redouble->is_changed) {
                    //---we already set this student as fail for this year but the jurry made changed to pass
                   return '';//--student pass
                } else {
                   return  $check_redouble->redouble_name;
                }

            } else {
                //---because his final average score is lowwer than 30 ...he/she must fail in this year ....so we create redouble record
                $this->createRedoubleRecord($idCardPointToStudent[$studentIdCard], $redName = trim($degreeName.$gradeId), $academicYearId, $isChanged = false);
                return $degreeName.$gradeId;
            }
        }

    }


    public function updateStatusStudent($studentIdCard, $status) {
        $student = DB::table('students')->where('id_card', $studentIdCard)
            ->update(
                ['radie' => $status]
            );
        return $student;
    }

    public function createRedoubleRecord($student, $redName, $academicYearId, $isChanged ) {

        $redouble= Redouble::where('name_en', $redName)->first();
        $create = DB::table('redouble_student')
            ->insert(
                ['redouble_id' => $redouble->id, 'student_id' => $student->student_id,'academic_year_id' => $academicYearId, 'is_changed' => $isChanged]
            );


        return $create;
    }

    public function saveStudentResitAutomatic($studentRattrapages, $objectCourseAnnualByIds) {



        $resitStudentAnnual = new EloquentResitStudentAnnualRepository();
        foreach($studentRattrapages as $studentAnnualId =>  $subjectRattrapage) {

            $studentResitSocre = $this->getStudentResitScore($studentAnnualId);

            if($resitCourses = $studentResitSocre->get()) {

                $destroy = $studentResitSocre->delete();
                if($destroy) {

                    if(isset($subjectRattrapage['fail'])) {

                        $courseAnnualIds = $subjectRattrapage['fail'];
                        foreach($courseAnnualIds as $courseAnnualId) {

                            $input = [
                                'student_annual_id' => $studentAnnualId,
                                'course_annual_id' => $courseAnnualId,
                                'semester_id' => $objectCourseAnnualByIds[$courseAnnualId]->semester_id
                            ];

                            $resitStudentAnnual ->create($input);
                        }

                    } else {
                        //---store one student back

                        if($resitCourses) {

                            $true = true;
                            foreach($resitCourses as $course) {
                                if($true) {
                                    $input = [
                                        'student_annual_id' => $course->student_annual_id,
                                        'semester_id' => $objectCourseAnnualByIds[$courseAnnualId]->semester_id,
                                        'course_annual_id' => null
                                    ];
                                    $resitStudentAnnual->create($input);
                                    $true = false;
                                } else {
                                    break;
                                }
                            }

                        }

                    }
                }
            } else {

                if(isset($subjectRattrapage['fail'])) {

                    $courseAnnualIds = $subjectRattrapage['fail'];

                    foreach($courseAnnualIds as $courseAnnualId) {

                        $input = [
                            'student_annual_id' => $studentAnnualId,
                            'course_annual_id' => $courseAnnualId,
                            'semester_id' => $objectCourseAnnualByIds[$courseAnnualId]->semester_id
                        ];

                        $resitStudentAnnual->create($input);
                    }
                } else {

                    $input = [
                        'student_annual_id' => $studentAnnualId,
                        'course_annual_id' => null,
                        'semester_id' => $objectCourseAnnualByIds[$courseAnnualId]->semester_id
                    ];

                    $resitStudentAnnual->create($input);
                }
            }
        }

    }

    public function getStudentResitScore($studentAnnualId) {

        $studentScore = DB::table('resit_student_annuals')
            ->orderBy('id')
            ->where('student_annual_id', $studentAnnualId);

        return $studentScore;

    }

    public function selectedGroupByCourseAnnual($array_course_annual_ids) {

        $arrayGroups = [];
        $selectedGroups = collect(
            DB::table('course_annual_classes')
            ->where('course_session_id', null)
            ->whereNotNull('group_id')
            ->whereIn('course_annual_id', $array_course_annual_ids)
            ->select('course_annual_id', 'group_id')
            ->get()
        )->groupBy('course_annual_id');

        $groups = $selectedGroups->toArray();

        foreach($array_course_annual_ids as $annual_id) {
            if(isset($groups[$annual_id])) {
                $arrayGroups[$annual_id] =array_column( json_decode(json_encode($groups[$annual_id]), true), 'group_id');
            }
        }
        return $arrayGroups;
    }

    public function findPassOrFailStudentScore($request) {

        $courseAnnuals = $this->getCourseAnnually();

        $deptId = $request->department_id;
        $academicYearID = $request->academic_year_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $semesterId = $request->semester_id;
        $deptOptionId = $request->dept_option_id;


        if($deptId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', '=',$deptId);
        }
        if($academicYearID) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearID);
        }
        if($degreeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$gradeId);
        }
        if($semesterId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$deptOptionId);
        }
        $array_course_annual_ids = $courseAnnuals->lists('course_annual_id');

        $courseAnnuals = $courseAnnuals->orderBy('course_annuals.semester_id')->orderBy('course_annuals.name_en')->get();// note restricted order by semester this is very important to make dynamic table course of each year [if change there would have bugs]

        $groups = $this-> selectedGroupByCourseAnnual($array_course_annual_ids);

        $allProperties = $this->getCourseAnnualWithScore($array_course_annual_ids);
        $eachCourseAnnualScores = $allProperties['averages'];


        foreach($courseAnnuals as $courseAnnual) {

            //$groupByCourseAnnual = isset($groups[$courseAnnual->course_annual_id])?$groups[$courseAnnual->course_annual_id]:null;

            $filtered_students = $this->filtering_student_annual($courseAnnual, $groups); /*--public function in StudentTrait Class--*/

            foreach($filtered_students as $stu_dent) {

                $each_score = isset($eachCourseAnnualScores[$courseAnnual->course_annual_id])?(isset($eachCourseAnnualScores[$courseAnnual->course_annual_id][$stu_dent->student_annual_id])?$eachCourseAnnualScores[$courseAnnual->course_annual_id][$stu_dent->student_annual_id]->average:0):0;

                if($courseAnnual->is_counted_creditability) {

                    if($each_score < ScoreEnum::Under_30) {
                        $fail_subjects[$stu_dent->id_card]['fail'][] = array('credit'=> $courseAnnual->course_annual_credit, 'score'=> $each_score, 'course_annual_id' => $courseAnnual->course_annual_id, 'student_annual_id' => $stu_dent->student_annual_id);
                    } else {
                        $fail_subjects[$stu_dent->id_card]['pass'][] = array('credit'=> $courseAnnual->course_annual_credit, 'score'=> $each_score, 'course_annual_id' => $courseAnnual->course_annual_id, 'student_annual_id' => $stu_dent->student_annual_id);
                    }
                }

            }
        }

        return [
            'fail_or_pass' => $fail_subjects,
            'score_properties' => $allProperties,
            'course_annual' => $courseAnnuals,
            'course_annual_ids' => $array_course_annual_ids,
            'groups' => $groups
        ];
    }


    public function studentResitData($request) {

        $fullUrl = $request->fullUrl();
        $studentRattrapages=[];
        $studentRattrapagesByStudentAnnualId = [];/*---store student-annual-id as key with key pass and fail for rattrapage-course-annual---*/
        $studentIdCards = [];
        $courseProgramIds = [];
        $courseAnnualIds = [];
        $courseAnnualByProgram = [];
        $arrayCourseAnnualIds = [];

        $studentDataProperties = $this->findPassOrFailStudentScore($request);/*--this method is implemented in StudentScore Trait Class--*/
        $courseAnnuals = $studentDataProperties['course_annual'];

        $academic_year_id = $request->academic_year_id;
        $academicYear = DB::table('academicYears')->where('id', $academic_year_id)->first();


        $resitStudentAnnualFromDB =['resit_student' => []]; //$this->hasRattrapages($studentDataProperties['fail_or_pass'], $course_annual_ids= $studentDataProperties['course_annual_ids']);

        if(count($resitStudentAnnualFromDB['resit_student']) > 0) {//count($resitStudentAnnualFromDB['resit_student']) > 0

            $onlyResitCourseAnnuals = $resitStudentAnnualFromDB['resit_course_annual'];
            $students = DB::table('studentAnnuals')
                ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
                ->whereIn('studentAnnuals.id', $resitStudentAnnualFromDB['student_annual_id'])
                ->select(
                    'students.name_latin', 'students.id_card', 'students.id as student_id',
                    'studentAnnuals.id as student_annual_id'
                )->orderBy('students.name_latin', 'ASC')
                ->get();

            $courseAnnuals = $resitStudentAnnualFromDB['course_annual'];

            foreach($courseAnnuals as $courseAnnual) {

                if($semesterId = $request->semester_id) {

                    if($semesterId == $courseAnnual->semester_id) {

                        if($departmentOptionId = $request->dept_option_id) {

                            if($departmentOptionId == $courseAnnual->department_option_id) {

                                if($courseAnnual->is_counted_creditability) {

                                    $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                    $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                    $courseProgramIds[] = $courseAnnual->course_id;
                                }
                            }
                        } else {
                            if($courseAnnual->is_counted_creditability) {

                                $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                $courseProgramIds[] = $courseAnnual->course_id;
                            }
                        }
                    } else {

                        if($departmentOptionId = $request->dept_option_id) {
                            if($departmentOptionId == $courseAnnual->department_option_id) {

                                if($courseAnnual->is_counted_creditability) {

                                    $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                    $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                    $courseProgramIds[] = $courseAnnual->course_id;
                                }
                            }
                        } else {
                            if($courseAnnual->is_counted_creditability) {

                                $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                $courseProgramIds[] = $courseAnnual->course_id;
                            }
                        }
                    }
                } else {

                    if($departmentOptionId = $request->dept_option_id) {

                        if($departmentOptionId == $courseAnnual->department_option_id) {

                            if($courseAnnual->is_counted_creditability) {

                                $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                $courseProgramIds[] = $courseAnnual->course_id;
                            }
                        } else {
                            if($courseAnnual->is_counted_creditability) {

                                $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                                $courseAnnualIds[] = $courseAnnual->course_annual_id;
                                $courseProgramIds[] = $courseAnnual->course_id;
                            }
                        }
                    } else {

                        if($courseAnnual->is_counted_creditability) {

                            $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                            $courseAnnualIds[] = $courseAnnual->course_annual_id;
                            $courseProgramIds[] = $courseAnnual->course_id;
                        }

                    }

                }

            }
            $courseProgramIds = array_unique($courseProgramIds);
            $courseProgramIds = array_values($courseProgramIds);

            $coursePrograms = Course::whereIn('courses.id', $courseProgramIds)->get();

            $averages = $studentDataProperties['score_properties']['averages'];

            $studentRattrapages = $resitStudentAnnualFromDB['resit_student'];

        } else {

            $studentResitExam = $this->findResitStudentAutomatic($studentDataProperties['fail_or_pass']);

            //dd($studentResitExam);


            /*dd($studentResitExam['e20130014']);*/

            /*foreach($studentResitExam as $student_id => $resit) {

                if(isset($resit['resit_subject']['fail'])) {

                    $subjects = $resit['resit_subject'];

                    if(isset($subjects['pass'])) {

                        foreach($subjects as $keyPOrF => $subVal) {
                            foreach($subVal as $val) {

                                if($keyPOrF == 'fail') {
                                    $arrayCourseAnnualIds[] = $val['course_annual_id'];
                                }
                                $studentRattrapages[$student_id][$keyPOrF][] = $val['course_annual_id'];
                                $studentRattrapagesByStudentAnnualId[$val['student_annual_id']][$keyPOrF][] = $val['course_annual_id'];
                            }
                        }
                        $studentIdCards[] = $student_id;
                    } else {

                        //---students fail all subjects so we need to
                        //---calculate moyenne again if the score is upper than 30 student will be allowed to resit the exam else they must be redouble

                        $average = $this->calculateMoyenne($subjects);

                        if($average >= ScoreEnum::Under_30) {

                            //--allow student to resit
                            foreach($subjects['fail'] as  $fail) {

                                $arrayCourseAnnualIds[] = $fail['course_annual_id'];
                                $studentRattrapages[$student_id]['fail'][]= $fail['course_annual_id'];
                                $studentRattrapagesByStudentAnnualId[$fail['student_annual_id']]['fail'][] = $fail['course_annual_id'];
                            }
                            $studentIdCards[] = $student_id;
                        }
                        /// else ---do not count these student
                    }
                }
            }*/


            /*$arrayCourseAnnualIds = array_unique($arrayCourseAnnualIds);
            $arrayCourseAnnualIds = array_values($arrayCourseAnnualIds);

            $students = $this->getStudentByIdCardYearly($studentIdCards, $academic_year_id);

            $objectCourseAnnualByIds = [];

            foreach($courseAnnuals as $courseAnnual) {

                if($option = $request->dept_option_id) {
                    if($option == $courseAnnual->department_option_id) {
                        if($courseAnnual->is_counted_creditability) {
                            $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                            $courseProgramIds[] = $courseAnnual->course_id;
                            $objectCourseAnnualByIds[$courseAnnual->course_annual_id] =  $courseAnnual;
                        }
                    }
                } else {
                    if($courseAnnual->is_counted_creditability) {
                        $courseAnnualByProgram[$courseAnnual->course_id][] = $courseAnnual->course_annual_id;
                        $courseProgramIds[] = $courseAnnual->course_id;
                        $objectCourseAnnualByIds[$courseAnnual->course_annual_id] =  $courseAnnual;
                    }
                }
            }
            $courseProgramIds = array_unique($courseProgramIds);
            $courseProgramIds = array_values($courseProgramIds);
            $coursePrograms = Course::whereIn('courses.id', $courseProgramIds)->get();
            $averages = $studentDataProperties['score_properties']['averages'];*/


            /*------save student rattrapaer automatic-----*/
            //$this->saveStudentResitAutomatic($studentRattrapagesByStudentAnnualId, $objectCourseAnnualByIds);
            /*----end saving ----*/


            $onlyResitCourseAnnuals = $arrayCourseAnnualIds;

        }


        return [
            'resit_course_annual' => $onlyResitCourseAnnuals,
            'student' => $students,
            'courseprogram' => $coursePrograms,
            'student_rattrapage' => $studentRattrapages,
            'course_annual_by_program' => $courseAnnualByProgram,
            'academic_year' => $academicYear,
            'average' => $averages,
            'full_url' => $fullUrl
        ];

    }

    public function getStudentByIdCardYearly($array_studentIdCard,$academic_year_id ) {

        $students = DB::table('students')
            ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            ->whereIn('id_card', $array_studentIdCard)
            ->where('studentAnnuals.academic_year_id', $academic_year_id)
            ->select(
                'students.name_latin', 'students.id_card', 'students.id as student_id',
                'studentAnnuals.id as student_annual_id'
            )->orderBy('students.name_latin', 'ASC')
            ->get();

        return $students;
    }


    public function calculateMoyenne($array_subject) {

        $credit = 0;
        $score = 0;
        if(isset($array_subject['fail'])) {
            if(isset($array_subject['pass'])) {

                foreach($array_subject['fail'] as $fail) {
                    $credit= $credit+ $fail['credit'];
                    $score = $score + ($fail['score']* $fail['credit']);
                }

                foreach($array_subject['pass'] as $pass) {
                    $credit= $credit+ $pass['credit'];
                    $score = $score + ($pass['score']* $pass['credit']);
                }

                return $this->floatFormat($score/$credit);

            } else {

                foreach($array_subject['fail'] as $fail) {
                    $credit= $credit+ $fail['credit'];
                    $score = $score + ($fail['score']* $fail['credit']);
                }

                return $this->floatFormat($score/$credit);
            }

        } else {

            foreach($array_subject['pass'] as $pass) {
                $credit= $credit+ $pass['credit'];
                $score = $score + ($pass['score']* $pass['credit']);
            }

            return $this->floatFormat($score/$credit);
        }

    }


    public function findResitStudentAutomatic($array_fial_subject ) {

        $studentResitExam = [];
        foreach($array_fial_subject as $studentIdCard =>  $array) {
            $subjectRattrapages = $this->findRattrapageSubject($array);
            $studentResitExam[$studentIdCard] = ['resit_subject'=>$subjectRattrapages];
        }
        return $studentResitExam;
    }

    public function findRattrapageSubject($array) {

        $total_credit = 0;
        //----array with key fail ---mean the couse score is under 30---
        if(isset($array['fail'])) {

            if(isset($array['pass'])) {

                $validate_score = 0;

                foreach($array['fail'] as $fail) {

                    $total_credit = $total_credit + $fail['credit'];
                    $validate_score = $validate_score + (ScoreEnum::Pass_Moyenne * $fail['credit']);
                }
                foreach($array['pass'] as $pass) {

                    $total_credit = $total_credit + $pass['credit'];
                    $validate_score = $validate_score + ($pass['score'] * $pass['credit']);
                }
                $approximation_moyenne = $validate_score / $total_credit;
                if($approximation_moyenne < ScoreEnum::Aproximation_Moyenne) {//----55

                    if(count($array['pass']) > 0) {

                        $find_min = $this->findMin($array['pass']);

                        if($find_min['element']['score'] < ScoreEnum::Pass_Moyenne) {//---50

                            $mark_subjected[] = $find_min['element'];
                            $array['fail'][] = $find_min['element'];
                            unset($array['pass'][$find_min['index']]);
                            $array['pass'] = array_values($array['pass']);

                            //-----because the prediction score of subplementary _course were not to reach the approximation moyenne..
                            // so we need to find the lowest score from array['pass']---and consider as fail to enforce student to re-exam it
                            //---then we do the findRattrapageSubject again until student pass...and return number of subjects for student to re-exam
                            //dump($array);
                            return $this->findRattrapageSubject($array);
                        } else {

                            //----even student take more supplementary exam he/she still not able to reacher the score 55..so in case his/her score is biggger than 50 ..so we can let him or her a try..
                            if($approximation_moyenne > ScoreEnum::Pass_Moyenne) {
                                return $array;
                            } else {
                                //---fail all courses
                                return $array;
                            }
                        }
                    } else {
                        //---student have to take exam for all subject---
                        return $array;
                    }
                } else {

                    //---student take exam in amount of subject then he or she will pass
                    return $array;
                }

            } else {

                //---all subject score are under 30
                return $array;

            }
        } else {


            /*--check if the moyenne of student is under 50 and all subject are bigger than 30*/

            $average = $this->calculateMoyenne($array);

            if($average < ScoreEnum::Pass_Moyenne) {

                $array['fail'] = [];
                if(count($array['pass']) > 0) {

                    $find_min = $this->findMin($array['pass']);

                    if($find_min['element']['score'] < ScoreEnum::Pass_Moyenne) {

                        $array['fail'][]= $find_min['element'];
                        unset($array['pass'][$find_min['index']]);
                        $array['pass'] = array_values($array['pass']);

                        return $this->findRattrapageSubject($array); //---recuring this function again

                    } else {

                        return $array;
                    }
                } else {
                    return $array;
                }

            } else {

                return $array;
            }
        }

    }

    public function findMin($array_val) {// ---array_val :: pass

        $min = $array_val[0]['score'];
        $credit = $array_val[0]['credit'];
        $course_annual_id = $array_val[0]['course_annual_id'];
        $student_annual_id = $array_val[0]['student_annual_id'];
        $index = 0;

        for($in = 1; $in < count($array_val); $in++) {

            if($min > $array_val[$in]['score']) {
                $index = $in;
                $min = $array_val[$in]['score'];
                $credit = $array_val[$in]['credit'];
                $course_annual_id = $array_val[$in]['course_annual_id'];
                $student_annual_id = $array_val[$in]['student_annual_id'];
            }
        }
//        dump($min);
        return [
            'element' => ['score'=> $min, 'credit' => $credit, 'course_annual_id'=>$course_annual_id, 'student_annual_id' => $student_annual_id],
            'index'  => $index
        ];
    }

    public function getCourseAnnualWithProp($props)
    {
        $courseAnnuals = $this->getCourseAnnually();

        if($props['department_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', '=',$props['department_id']);
        }

        if($props['academic_year_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$props['academic_year_id']);
        }
        if($props['degree_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$props['degree_id']);
        }
        if($props['grade_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$props['grade_id']);
        }
        if($props['semester_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$props['semester_id']);
        }
        if($props['dept_option_id']) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$props['dept_option_id']);
        }

        $courseAnnuals = $courseAnnuals->orderBy('course_annuals.semester_id')->orderBy('course_annuals.name_en');// note restricted order by semester this is very important to make dynamic table course of each year [if change there would have bugs]


        return $courseAnnuals;

    }

    public function hasRattrapages($array_fial_subject, $coursenAnnualIds ) {



        $idCardPointToIdAnnual = [];
        $studentAnnualIds = [];
        $resitStudentAnnualIDs = [];
        $onlyResitSubjects=[];
        $studentResits = [];

        $studentWithCourseAnnuals = [];

        foreach($array_fial_subject as $key => $subjects) {

            if(isset($subjects['fail'])) {

                if(isset($subjects['pass'])) {

                    $studentAnnualIds[] = $subjects['fail'][0]['student_annual_id'];
                    $idCardPointToIdAnnual[$subjects['fail'][0]['student_annual_id']] = $key;

                    foreach($subjects['fail'] as $fail) {

                        $studentWithCourseAnnuals[$fail['student_annual_id']][] = $fail['course_annual_id'];
                    }
                    foreach($subjects['pass'] as $pass) {

                        $studentWithCourseAnnuals[$pass['student_annual_id']][] = $pass['course_annual_id'];
                    }
                } else {
                    $studentAnnualIds[] = $subjects['fail'][0]['student_annual_id'];
                    $idCardPointToIdAnnual[$subjects['fail'][0]['student_annual_id']] = $key;
                    foreach($subjects['fail'] as $fail) {

                        $studentWithCourseAnnuals[$fail['student_annual_id']][] = $fail['course_annual_id'];
                    }
                }
            }else {

                $studentAnnualIds[] = $subjects['pass'][0]['student_annual_id'];
                $idCardPointToIdAnnual[$subjects['pass'][0]['student_annual_id']] =  $key;
                foreach($subjects['pass'] as $pass) {

                    $studentWithCourseAnnuals[$pass['student_annual_id']][] = $pass['course_annual_id'];
                }
            }

        }

        /*----resit student course annuals from database=------ */

        $findResit = DB::table('resit_student_annuals')
            ->where(function($query) use ($studentAnnualIds, $coursenAnnualIds) {
                $query ->whereIn('student_annual_id', $studentAnnualIds)
                    ->whereIn('course_annual_id', $coursenAnnualIds)
                    ->OrwhereNull('course_annual_id');
            })->get();

        $findResit = collect($findResit)->groupBy('semester_id')->toArray();

        $status = false;
        $allResitStudentAnnuals =[];

        if(count($findResit) > 0) {
            $allResitStudentAnnuals= array_merge($allResitStudentAnnuals , $findResit);
            $status = true;
        } else {
            $status = false;
        }

        if($status) {
            $studentAnnualWithResitCourseAnnuals = [];
            if($allResitStudentAnnuals) {

                foreach($allResitStudentAnnuals as $resit) {
                    $resitStudentAnnualIDs[] = $resit->student_annual_id;
                    $studentAnnualWithResitCourseAnnuals[$resit->student_annual_id][] = $resit->course_annual_id;
                    $onlyResitSubjects[] = $resit->course_annual_id;;
                }
                foreach($studentAnnualWithResitCourseAnnuals as $studentAnnualId => $resitCourse) {

                    $courseDifferences = array_diff($studentWithCourseAnnuals[$studentAnnualId], $resitCourse);
                    $resitStudents[$idCardPointToIdAnnual[$studentAnnualId]]['fail'] = $resitCourse;
                    $resitStudents[$idCardPointToIdAnnual[$studentAnnualId]]['pass'] = array_values($courseDifferences);

                }
                $onlyResitSubjects = array_unique($onlyResitSubjects);
                $onlyResitSubjects = array_values($onlyResitSubjects);
            } else {
                $resitStudents=[];
                $onlyResitSubjects=[];
            }
        } else {
            $resitStudents=[];
            $onlyResitSubjects=[];
        }


        return [
            'resit_course_annual' => $onlyResitSubjects,
            'student_annual_id' => $resitStudentAnnualIDs,
            'resit_student' => $resitStudents
        ];
    }


    public function getCourseProAndAnnual($deptId,$academicYearID, $degreeId,$gradeId ,$semesterId, $deptOptionId) {


        $courseAnnuals = $this->getCourseAnnually();

        if($deptId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', '=',$deptId);
        }

        if($academicYearID) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearID);
        }
        if($degreeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$gradeId);
        }
        if($semesterId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$deptOptionId);
        }

        $courseAnnuals = $courseAnnuals->orderBy('course_annuals.semester_id')->orderBy('course_annuals.name_en')->get();// note restricted order by semester this is very important to make dynamic table course of each year [if change there would have bugs]

        return ['course_annual' => $courseAnnuals, 'course_program' => []];

    }


    public function getStudentScoreYearly($studentIdCard, $academicYearId) {

        $student = Student::where('id_card', $studentIdCard)->first();
        $studentAnnuals = $student->studentAnnuals;


    }


    public function generating_student_redouble($each_course, $stu_dent, $each_score, $fail_subjects) {

        if($each_course->is_counted_creditability) {

            if($each_score < ScoreEnum::Under_30) {

                //$check_redouble = $this->checkRedouble($stu_dent, $each_course->academic_year_id);//---check this current year if student has been change in redouble
                /*----create one redouble record for this student ----*/
                /*---before create redouble record, we have to check if the student has already marked as redouble for this year --then we skip ---*/
                /*if($check_redouble === null) {
                    if($each_course->degree_id == ScoreEnum::Degree_I) {
                        $this->createRedoubleRecord($stu_dent, $redName = trim(ScoreEnum::Red_I.$each_course->grade_id), $each_course->academic_year_id, $isChanged = false);
                    } else {
                        $this->createRedoubleRecord($stu_dent, $redName = trim(ScoreEnum::Red_T.$each_course->grade_id), $each_course->academic_year_id, $isChanged = false);
                    }
                }*/
                $fail_subjects[$stu_dent->id_card]['fail'][] = array('credit'=> $each_course->course_annual_credit, 'score'=> $each_score, 'course_annual_id' => $each_course->course_annual_id, 'student_annual_id' => $stu_dent->student_annual_id);
            } else {
                $fail_subjects[$stu_dent->id_card]['pass'][] = array('credit'=> $each_course->course_annual_credit, 'score'=> $each_score, 'course_annual_id' => $each_course->course_annual_id, 'student_annual_id' => $stu_dent->student_annual_id);
            }
        }

        return $fail_subjects;

    }

    private function createScorePercentage($midterm, $final, $courseAnnualId)
    {

        $check = 0;
        $courseAnnual = CourseAnnual::where('id', $courseAnnualId)->first();
        if ($midterm > 0) {
            $percentageInput = [
                [
                    'name' => 'Midterm-' . $midterm . '%',
                    'percent' => $midterm,
                    'percentage_type' => 'normal'
                ],
                [
                    'name' => 'Final-' . $final . '%',
                    'percent' => $final,
                    'percentage_type' => 'normal'
                ]
            ];
        } else {

            $percentageInput = [
                [
                    'name' => 'Final-' . $final . '%',
                    'percent' => $final,
                    'percentage_type' => 'normal'
                ]
            ];

        }
        $propArrayIds = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnual);

        $department_ids = $propArrayIds['department_id'];
        $degree_ids = $propArrayIds['degree_id'];
        $grade_ids = $propArrayIds['grade_id'];
        $department_option_ids = $propArrayIds['department_option_id'];
        $groups = $propArrayIds['group'];
        $department = Department::whereIn('id', $department_ids)->first();



        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId($department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id);


        if (count($department_option_ids) > 0) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }
        if ($groups) {

            $studentAnnualIds = DB::table('group_student_annuals')->whereIn('group_id', $groups)
                ->where('semester_id', $courseAnnual->semester_id);


            /*--case to find student in another group of the Session Anglai and french ---*/

            if($department->is_vocational) {
                $studentAnnualIds = $studentAnnualIds->where('department_id', $department->id)->lists('student_annual_id');
            } else {
                $studentAnnualIds = $studentAnnualIds->lists('student_annual_id');
            }

            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)
                ->orderBy('students.name_latin');

            if($courseAnnual->semester_id >  1) {

                $studentByCourse = $studentByCourse
                    ->where(function($query) {
                        $query->where('students.radie','=',  false)
                            ->orWhereNull('students.radie');
                    })
                    ->orderBy('students.name_latin');

            } else {
                $studentByCourse = $studentByCourse->orderBy('students.name_latin');

            }
        } else {

            /*---here the session anglai and french must have student in group --otherwise it is a department of specialist ---*/
            if(!$department->is_vocational) {
                if($courseAnnual->semester_id >  1) {

                    $studentByCourse = $studentByCourse
                        ->where(function($query) {
                            $query->where('students.radie','=',  false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin');

                } else {
                    $studentByCourse = $studentByCourse->orderBy('students.name_latin');
                }

            }
        }
        $studentByCourse = $studentByCourse->get();

        $listStudentIds  = collect($studentByCourse)->pluck('student_id')->toArray();
        $redoubleStudents = $this->redoubleByStudentIds($listStudentIds, $courseAnnual->academic_year_id);

        foreach ($percentageInput as $input) {

            $savePercentageId = $this->percentages->create($input);// return the percentage id

            if ($studentByCourse) {
                foreach ($studentByCourse as $studentScore) {
                    if(!isset($redoubleStudents[$studentScore->student_id])) {

                        $input = [
                            'course_annual_id' => $courseAnnualId,
                            'student_annual_id' => $studentScore->student_annual_id,
                            'department_id' => $courseAnnual->department_id,
                            'degree_id' => $courseAnnual->degree_id,
                            'grade_id' => $courseAnnual->grade_id,
                            'academic_year_id' => $courseAnnual->academic_year_id,
                            'semester_id' => $courseAnnual->semester_id,
                            'socre_absence' => null,
                            'percentage_id' => [$savePercentageId->id]
                        ];

                        $saveScoreId = $this->courseAnnualScores->create($input);// return the socreId
                        //$savePercentageScore = $this->courseAnnualScores->createPercentageScore($saveScoreId->id, $savePercentageId->id);
                        if ($saveScoreId) {
                            $check++;
                        }

                    }

                }
            }
        }

        if ($check == (count($studentByCourse) * count($percentageInput))) {
            return true;
        } else {
            return false;
        }

    }



    private function initScoreRecord($courseAnnual, $groupIds, $midterm, $final) {



        $check = 0;
        if ($midterm > 0) {
            $percentageInput = [
                [
                    'name' => 'Midterm-' . $midterm . '%',
                    'percent' => $midterm,
                    'percentage_type' => 'normal'
                ],
                [
                    'name' => 'Final-' . $final . '%',
                    'percent' => $final,
                    'percentage_type' => 'normal'
                ]
            ];
        } else {

            $percentageInput = [
                [
                    'name' => 'Final-' . $final . '%',
                    'percent' => $final,
                    'percentage_type' => 'normal'
                ]
            ];

        }

        $groups = $groupIds;
        $department = Department::whereIn('id', [$courseAnnual->department_id])->first();

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId([$courseAnnual->department_id], [$courseAnnual->degree_id], [$courseAnnual->grade_id], $courseAnnual->academic_year_id);

        if ($courseAnnual->department_option_id) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.department_option_id', $courseAnnual->department_option_id);
        }
        if ($groups) {

            $studentAnnualIds = DB::table('group_student_annuals')->whereIn('group_id', $groups)
                ->where('semester_id', $courseAnnual->semester_id);

            /*--case to find student in another group of the Session Anglai and french ---*/

            if($department->is_vocational) {
                $studentAnnualIds = $studentAnnualIds->where('department_id', $department->id)->lists('student_annual_id');
            } else {
                $studentAnnualIds = $studentAnnualIds->lists('student_annual_id');
            }

            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)
                ->orderBy('students.name_latin');

            if($courseAnnual->semester_id >  1) {

                $studentByCourse = $studentByCourse
                    ->where(function($query) {
                        $query->where('students.radie','=',  false)
                            ->orWhereNull('students.radie');
                    })
                    ->orderBy('students.name_latin');

            } else {
                $studentByCourse = $studentByCourse->orderBy('students.name_latin');

            }
        } else {

            /*---here the session anglai and french must have student in group --otherwise it is a department of specialist ---*/
            if(!$department->is_vocational) {
                if($courseAnnual->semester_id >  1) {

                    $studentByCourse = $studentByCourse
                        ->where(function($query) {
                            $query->where('students.radie','=',  false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin');

                } else {
                    $studentByCourse = $studentByCourse->orderBy('students.name_latin');
                }

            }
        }
        $studentByCourse = $studentByCourse->get();

        $listStudentIds  = collect($studentByCourse)->pluck('student_id')->toArray();
        $redoubleStudents = $this->redoubleByStudentIds($listStudentIds, $courseAnnual->academic_year_id);

        foreach ($percentageInput as $input) {

            $savePercentageId = $this->percentages->create($input);// return the percentage id

            if ($studentByCourse) {
                foreach ($studentByCourse as $studentScore) {
                    if(!isset($redoubleStudents[$studentScore->student_id])) {

                        $input = [
                            'course_annual_id' => $courseAnnual->id,
                            'student_annual_id' => $studentScore->student_annual_id,
                            'department_id' => $courseAnnual->department_id,
                            'degree_id' => $courseAnnual->degree_id,
                            'grade_id' => $courseAnnual->grade_id,
                            'academic_year_id' => $courseAnnual->academic_year_id,
                            'semester_id' => $courseAnnual->semester_id,
                            'socre_absence' => null,
                            'percentage_id' => [$savePercentageId->id]
                        ];

                        $saveScoreId = $this->courseAnnualScores->create($input);// return the socreId
                        //$savePercentageScore = $this->courseAnnualScores->createPercentageScore($saveScoreId->id, $savePercentageId->id);
                        if ($saveScoreId) {
                            $check++;
                        }

                    }

                }
            }
        }

        if ($check == (count($studentByCourse) * count($percentageInput))) {
            return true;
        } else {
            return false;
        }

    }
}