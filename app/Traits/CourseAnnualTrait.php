<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 5/5/17
 * Time: 1:45 PM
 */

namespace App\Traits;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Enum\ScoreEnum;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

trait CourseAnnualTrait
{
    /* protected $course_annual_repo;
     protected $course_session_repo;
     protected $course_annual_class_repo;
     public function __construct(
         CourseAnnualRepositoryContract $courseAnnualContract,
         CourseSessionRepositoryContract $courseSessionContract,
         CourseAnnualClassRepositoryContract $courseAnnualClassContract
         )
     {
         $this->course_annual_repo = $courseAnnualContract;
         $this->course_session_repo = $courseSessionContract;
         $this->course_annual_class_repo = $courseAnnualClassContract;

     }*/

    public function generate_course_annual($request)
    {

        $courseAnnuals = DB::table('course_annuals')->where('course_annuals.academic_year_id', ($request->academic_year_id - 1));

        $departmentId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $check = 0;
        $unCheck = 0;
        if ($departmentId) {

            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', '=', $departmentId);
        }
        if ($degreeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=', $degreeId);
        }
        if ($gradeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=', $gradeId);
        }

        $courseAnnualIds = $courseAnnuals->lists('course_annuals.id');
        $courseSessions = DB::table('course_sessions')->whereIn('course_annual_id', $courseAnnualIds);
        $courseSessionIds = $courseSessions->lists('course_sessions.id');
        $courseSessions = collect($courseSessions->get())->groupBy('course_annual_id')->toArray();

        $courseAannualClassesBySession = DB::table('course_annual_classes')
            ->whereNull('course_annual_id')
            ->whereIn('course_session_id', $courseSessionIds)
            ->get();
        $courseAannualClassesByAnnual = DB::table('course_annual_classes')
            ->whereNull('course_session_id')
            ->whereIn('course_annual_id', $courseAnnualIds)
            ->get();
        $courseAannualClassesByAnnual = collect($courseAannualClassesByAnnual)->groupBy('course_annual_id')->toArray();
        $courseAannualClassesBySession = collect($courseAannualClassesBySession)->groupBy('course_session_id')->toArray();
        $courseAnnuals = $courseAnnuals->get();

        /*--create course annual for requested academic year----*/
        foreach ($courseAnnuals as $course) {

            $input = (array)$course;
            $input['academic_year_id'] = (int)$request->academic_year_id;

            // check for preventing a sencond time of generating Course Annual
            $isGenerated = $this->isCourseAnnualGenerated($course->course_id, $course->semester_id, $request->academic_year_id, $course->department_id, $course->degree_id, $course->grade_id, $course->employee_id);
            if ($isGenerated) {
                $unCheck++;
                continue;
            } else {
                $store = $this->courseAnnuals->create($input);

                /*---create course session by course annual ---*/
                if (isset($courseSessions[$course->id])) {
                    /*--create new course session for the generated course annual---*/
                    foreach ($courseSessions[$course->id] as $courseSession) {
                        $sessionInput = (array)$courseSession;
                        $sessionInput['course_annual_id'] = $store->id;
                        $saveCourseSession = $this->courseSessions->create($sessionInput);
                        /*---create course annual class by previous course session but change to the generated one ---*/

                        if (isset($courseAannualClassesBySession[$courseSession->id])) {

                            $encode_course = json_encode($courseAannualClassesBySession[$courseSession->id]);
                            $decode_course = json_decode($encode_course, true);
                            $sessionclassInput = [
                                'groups' => array_column($decode_course, 'group_id'),
                                'course_session_id' => $saveCourseSession->id
                            ];
                            $saveCourseAnnualClassBySession = $this->courseAnnualClasses->create($sessionclassInput);
                        }

                    }
                }

                /*----create course annual class by course annual ---*/
                if (isset($courseAannualClassesByAnnual[$course->id])) {
                    $json = json_encode($courseAannualClassesByAnnual[$course->id]);
                    $decode = json_decode($json, true);
                    $annualclassInput = [
                        'groups' => array_column($decode, 'group_id'),
                        'course_annual_id' => $store->id
                    ];
                    $saveCourseAnnualClassByAnnual = $this->courseAnnualClasses->create($annualclassInput);
                }

                if ($store) {
                    $check++;
                }
            }
        }

        if ($check == count($courseAnnuals) - $unCheck) {

            return ['status' => true, 'message' => 'Course Annual Generated!!'];

        } else {
            return ['status' => true, 'message' => 'Course Annual Not Generated!!'];
        }

    }

    public function isCourseAnnualGenerated($courseId, $semesterId, $academicYearId, $departmentId, $degreeId, $gradeId, $employeeId)
    {

        $select = DB::table('course_annuals')
            ->where([
                ['course_id', $courseId],
                ['semester_id', $semesterId],
                ['academic_year_id', $academicYearId],
                ['department_id', $departmentId],
                ['degree_id', $degreeId],
                ['grade_id', $gradeId],
                ['employee_id', $employeeId]
            ])
            ->get();
        if ($select) {
            return true;
        } else {
            return false;
        }

    }

    public function getAvailableCourse($deptId, $academicYearId, $semesterId)
    {

        $availableCourses = DB::table('course_annuals')
            ->leftJoin('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
            ->leftJoin('departments', 'departments.id', '=', 'course_annuals.department_id')
            ->leftJoin('grades', 'grades.id', '=', 'course_annuals.grade_id')
            ->select('course_annuals.id as course_annual_id', 'course_annuals.academic_year_id', 'course_annuals.semester_id',
                'course_annuals.employee_id', 'course_annuals.name_en', 'course_annuals.name_kh', 'course_annuals.name_fr',
                'course_annuals.credit', 'course_annuals.course_id', 'course_annuals.time_course', 'course_annuals.time_td', 'course_annuals.time_tp',
                'course_annuals.grade_id', 'course_annuals.degree_id', 'course_annuals.department_id', 'course_annuals.department_option_id',
                'course_annual_classes.group', 'departments.code as department_code',
                'grades.name_en as grade_name'
            )
            ->where([
                ['course_annuals.academic_year_id', $academicYearId],
                ['course_annuals.semester_id', $semesterId]
            ]);

        if ($deptId) {
            $availableCourses = $availableCourses->where('course_annuals.department_id', $deptId);
        }

        return $availableCourses;
    }

    public function dataSendToView($courseAnnualId)
    {

        $courseAnnual = DB::table('course_annuals')->where('id', $courseAnnualId)->first();

        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $availableCourses = $this->getAvailableCourse($dept = null, $courseAnnual->academic_year_id, $courseAnnual->semester_id);

        if (auth()->user()->allow("view-all-score-in-all-department") || auth()->user()->allow('view-all-score-course-annual')) {

            $availableCourses = $availableCourses->orderBy('course_annuals.id')->get();
        } else {
            if (auth()->user()->allow("input-score-course-annual")) { // only teacher in every department who have this permission

                $availableCourses = $availableCourses->where('employee_id', $employee->id)->orderBy('course_annuals.id')->get();

            } else {
                $availableCourses = $availableCourses->orderBy('course_annuals.id')->get();
            }
        }

        $selectedCourses = [];

        foreach ($availableCourses as $availableCourse) {
            $selectedCourses[$availableCourse->course_annual_id][] = $availableCourse;
        }

        return [
            'course_annual' => $courseAnnual,
            'available_course' => $selectedCourses
        ];
    }


    public function cloneScorePanel(Request $request)
    {

        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();
        $department = Department::where('id', $courseAnnual->department_id)->first();
        $degree = Degree::where('id', $courseAnnual->degree_id)->first();
        $grade = Grade::where('id', $courseAnnual->grade_id)->first();
        $academicYear = AcademicYear::where('id', $courseAnnual->academic_year_id)->first();

        $groups = $courseAnnual->courseAnnualClass()->join('groups', function ($query) {
            $query->on('course_annual_classes.group_id', '=', 'groups.id');
        })
            ->select('groups.*')
            ->orderBy('groups.code', 'ASC')
            ->get()->toArray();

        usort($groups, function ($a, $b) {
            if(is_numeric($a['code'])) {
                return $a['code'] - $b['code'];
            } else {
                return strcmp($a['code'], $b['code']);
            }
        });
        $groups = array_chunk($groups, 8);

        if($courseAnnual->responsible_department_id) {

            $coursePrograms = DB::table('course_annuals')->join('courses', function ($query) use($courseAnnual) {
                $query->on('courses.id', '=', 'course_annuals.course_id')
                    ->where('course_annuals.department_id', '=', $courseAnnual->responsible_department_id)
                    ->where('course_annuals.degree_id', '=', $courseAnnual->degree_id)
                    ->where('course_annuals.grade_id', '=', $courseAnnual->grade_id)
                    ->where('course_annuals.semester_id', '=', $courseAnnual->semester_id)
                    ->where('course_annuals.academic_year_id', '=', $courseAnnual->academic_year_id);

            })->get();
        }
        return view('backend.course.courseAnnual.includes.popup_clone_score_panel', compact(
            'groups', 'courseAnnual', 'department', 'degree', 'grade', 'academicYear', 'coursePrograms'
        ));

    }

    public function cloneScore(Request $request)
    {

        $groups = $request->group_id;
        $count = 0;
        $countUpdate = 0;
        $select = [
            'scores.course_annual_id', 'scores.student_annual_id',
            'scores.score', 'scores.score_absence', 'percentages.name',
            'percentages.percent', 'percentages.id as percentage_id',
            'scores.id as score_id'
        ];

        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();

        if($courseAnnual->responsible_department_id) {


            if(isset($groups) && $groups != '') {

                $studentAnnualIdByGroups = DB::table('group_student_annuals')
                    ->where('semester_id', $courseAnnual->semester_id)
                    ->where('department_id', $courseAnnual->responsible_department_id)
                    ->whereIn('group_id', is_array($groups) ?$groups:[$groups])
                    ->lists('student_annual_id');
            } else {
                $studentAnnualIdByGroups = [];
            }


            if(count($studentAnnualIdByGroups) > 0) {


                $scoreCourseAnnualProp =DB::table('scores')
                    ->join('percentage_scores', function($query) use($courseAnnual, $studentAnnualIdByGroups) {
                        $query->on('percentage_scores.score_id', '=', 'scores.id')
                            ->where('scores.course_annual_id','=', $courseAnnual->id)
                            ->whereIn('scores.student_annual_id', $studentAnnualIdByGroups);
                    })
                    ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
                    ->select($select)
                    ->orderBy('percentages.id')
                    ->get();

            } else {

                $scoreCourseAnnualProp = DB::table('scores')
                    ->join('percentage_scores', function($query) use($courseAnnual) {
                        $query->on('percentage_scores.score_id', '=', 'scores.id')
                            ->where('scores.course_annual_id','=', $courseAnnual->id);
                    })
                    ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
                    ->select($select)
                    ->orderBy('percentages.id')
                    ->get();
            }


            $scoreAbsence = $this->getAbsenceFromDB($courseAnnual->id);// the absence of student for the course of thier department

            $scoreCourseAnnualProp = collect($scoreCourseAnnualProp)->groupBy('student_annual_id')->toArray();
            $resDepartment = Department::where('id', $courseAnnual->responsible_department_id)->first();

            if($resDepartment->is_vocational) {

                if(isset($courseAnnual->reference_course_id)) {

                    /*---these are score that inputted from the SA or SF. and we need these score to update for student in each department ---*/
                    $courseAnnualIds = CourseAnnual::where('course_id', $courseAnnual->reference_course_id)->lists('id')->toArray();

                    $absenceByCourses = $this->getAbsenceFromDB($courseAnnualIds); //---student absence key student annual id

                    $percentages = DB::table('scores')
                        ->join('percentage_scores', function($query) use($courseAnnualIds) {
                            $query->on('percentage_scores.score_id', '=', 'scores.id')
                                ->whereIn('scores.course_annual_id', $courseAnnualIds);
                        })->get();
                    /*
                        ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
                        ->select($select)
                        ->orderBy('percentages.id')
                        ->get();*/

                    dd($percentages);

                    $collection = collect($percentages);
                    $toCloneScoreProps = $collection->groupBy('student_annual_id')->toArray();

                    /*----end score from depatment SA or SF----*/


                    /*---loop course score that we need to update for student (consit of student annual id ) ----*/
                    foreach($scoreCourseAnnualProp as $studentAnnualId =>  $scoreProp) {
                        /*--there are two type of score. 1 midterm and 2 final score --*/

                        //check if the score is inputed by SA or SF---
                        if(isset($toCloneScoreProps[$studentAnnualId])) {

                            $scoreToCopies = $toCloneScoreProps[$studentAnnualId];

                            foreach($scoreProp as $index =>  $prop) {

                                $scoreItem = collect($scoreToCopies)->filter(function($item) use($index, $prop) {
                                    if($item->percent == $prop->percent) {
                                        return $item;
                                    }
                                })->toArray();

                                if(count($scoreItem) > 0) {

                                    /*---ScoreItem has only one vlaue but the index is not 0----*/

                                    foreach($scoreItem as $item) {
                                        $scoreItem = $item;
                                    }
                                    /*--end--*/

                                    if($scoreItem->score != null && $scoreItem->score != '') {

                                        $input = [
                                            'score' =>$scoreItem->score,
                                            'score_absence' => $scoreItem->score_absence
                                        ];
                                        $update =  $this->courseAnnualScores->update($prop->score_id, $input);

                                        if($update) {
                                            $countUpdate++;
                                        }

                                    } else {
                                        $count += (1/2);
                                    }

                                } else {

                                    $edit_url = route('admin.course.course_annual.edit', $courseAnnual->id);

                                    $percen_message = ' ';

                                    foreach($scoreToCopies as $propItem) {
                                        $percen_message = $percen_message.' '. $propItem->name;
                                    }

                                    return Response::json(['status' => false, 'message' => trans('alerts.backend.course_annual.score.clone.different_percentage', ['percent' => $percen_message ]), 'edit_url' => $edit_url]);
                                }
                            }

                        } else {
                            $count++;
                        }

                        /*---end cloning the score record ---*/

                        //--start cloning score absence and notation
                        /*----clone absence and notation for the student annual ---*/

                        if(count($scoreAbsence) > 0) {
                            if(isset($scoreAbsence[$courseAnnual->id])) {

                                $absence = $scoreAbsence[$courseAnnual->id];

                                if(isset($absence[$studentAnnualId])) {

                                    /*--there is an absence record for this student so we need to update the absence ---*/
                                    $input = [
                                        'num_absence' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->num_absence:null,
                                        'notation' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->notation:null
                                    ];
                                    $this->absences->update($absence[$studentAnnualId]->id, $input);

                                } else {

                                    /*---there is no record absence of this student so we need to check if we should clone from Absence by course inputted from SA or SF--*/

                                    $input = [
                                        'course_annual_id' => $courseAnnual->id,
                                        'student_annual_id' => $studentAnnualId,
                                        'num_absence' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->num_absence:null,
                                        'notation' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->notation:null
                                    ];
                                    if(isset($absenceByCourses[$studentAnnualId])) {
                                        /*---create absence record ----*/
                                        $this->absences->create($input);
                                        /*--end create number absence ---*/
                                    }
                                }
                            }
                        } else {

                            $input = [
                                'course_annual_id' => $courseAnnual->id,
                                'student_annual_id' => $studentAnnualId,
                                'num_absence' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->num_absence:null,
                                'notation' => isset($absenceByCourses[$studentAnnualId])?$absenceByCourses[$studentAnnualId]->notation:null
                            ];

                            if(isset($absenceByCourses[$studentAnnualId])) {
                                /*---create absence record ----*/
                                $this->absences->create($input);
                                /*--end create number absence ---*/
                            }
                        }
                    }

                    /*---end loop of the student that we need to update score ----*/

                    if(($countUpdate/2) == count($scoreCourseAnnualProp)) {

                        return Response::json(['status' => true, 'message' => trans('alerts.backend.course_annual.score.clone.success')]);
                    }

                    if($countUpdate > 0) {

                        return Response::json(['status' => true, 'message' => trans('alerts.backend.course_annual.score.clone.miss_some_score')]);
                    }

                    /*---check if the score  is not inputted from SA or SF. */
                    if($count == count($scoreCourseAnnualProp)) {
                        return Response::json(['status' => false, 'message' => trans('alerts.backend.course_annual.score.clone.not_input_yet', ['dept' => $resDepartment->name_en])]);
                    }

                } else {
                    $edit_url = route('admin.course.course_annual.edit', $courseAnnual->id);
                    return Response::json(['status' => false, 'message' => trans('alerts.backend.course_annual.score.clone.no_reference_course'), 'edit_url' =>$edit_url]);

                }

            } else {

                $edit_url = route('admin.course.course_annual.edit', $courseAnnual->id);

                return Response::json(['status' => false, 'message' => trans('alerts.backend.course_annual.score.clone.no_respsonsible_dept' ), 'edit_url' =>$edit_url]);
            }

        } else {

            return Response::json(['status' => false, 'message' => trans('alerts.backend.course_annual.score.clone.not_a_vocational')]);
        }
    }

    public function isAllowCloningCourse(Request $request)
    {
        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();

        if($courseAnnual->is_allow_scoring) {
            if($courseAnnual->responsible_department_id) {

                $resDepartment = Department::where('id', $courseAnnual->responsible_department_id)->first();
                if($resDepartment->is_vocational) {
                    return Response::json(['status' => true, 'message' => 'Allow','allow_scoring'=> true, 'code' => 200]);
                } else {
                    return Response::json(['status' => false, 'message' => 'Not allow!', 'allow_scoring'=> true, 'code' => 201]);
                }
            } else {
                return Response::json(['status' => false, 'message' => 'Not allow!', 'allow_scoring'=> true, 'code' => 202]);
            }
        } else {
            if($courseAnnual->responsible_department_id) {

                $resDepartment = Department::where('id', $courseAnnual->responsible_department_id)->first();
                if($resDepartment->is_vocational) {
                    return Response::json(['status' => true, 'message' => 'Allow', 'allow_scoring'=> false, 'code' => 200]);
                } else {
                    return Response::json(['status' => false, 'message' => 'Not allow!', 'allow_scoring'=> false, 'code' => 201]);
                }
            } else {
                return Response::json(['status' => false, 'message' => 'Not allow!', 'allow_scoring'=> false, 'code' => 202]);
            }
        }
    }

    public function loadReferenceCourse(Request $request)
    {

        if(isset($request->course_annual_id)) {
            $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();
        } else{
            $courseAnnual = null;
        }

        $data = [];
        if($request->department_id != null && $request->department_id != '') {
            $department = Department::where('id', $request->department_id)->first();

            $courses = Course::where('department_id', $request->department_id);

            if($request->degree_id != null && $request->degree_id != '') {
                $courses = $courses->where('degree_id', $request->degree_id);
            }

            if($request->grade_id != null && $request->grade_id != '') {

                $courses = $courses->where('grade_id', $request->grade_id);
            }
            $courses = $courses->get();

            $element = [
                'text'=>  $department->code,
            ];
            foreach($courses as $course) {

                $childrens = [
                    'id'=> $course->id,
                    'text'=> (($course->degree_id == ScoreEnum::Degree_I)?'I':'T').$course->grade_id."-S".$course->semester_id." | ".$course->name_en,
                    'selected' => isset($courseAnnual->reference_course_id) ? (($course->id == $courseAnnual->reference_course_id) ? true:false ) :false,
                    'value' => $course->id
                ];

                $element['children'][] = $childrens;

            }
            $data[] = $element;

            return Response::json(['status' => true, 'data' => $data]);

        } else {
            return Response::json(['status' => false, 'message' => 'No selected responsible department!']);
        }
    }

}