<?php
/**
 * Created by PhpStorm.
 * User: imac-04
 * Date: 4/26/17
 * Time: 1:53 PM
 */

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use Illuminate\Support\Facades\Response;
use App\Models\Semester;
use App\Models\Enum\SemesterEnum;
trait CourseSessionTrait
{


    public function courseSessionByTeacherFromDB($academicYearId, $grade_id, $degree_id) {

        $arrayCourses = [];

        $courseSessions = $this->getCourseSessionFromDB();
        $courseSessions = $courseSessions->where('course_annuals.academic_year_id', $academicYearId);
        if($degree_id) {
            $courseSessions = $courseSessions->where('course_annuals.degree_id', '=',$degree_id);
        }
        if($grade_id) {
            $courseSessions = $courseSessions->where('course_annuals.grade_id', '=',$grade_id);
        }
        $courseSessions = $courseSessions->get();


        foreach($courseSessions as $courseSession) {
            if($courseSession->lecturer_id != null) {
                $arrayCourses[$courseSession->lecturer_id][] = $courseSession;
            }

        }
        return $arrayCourses;
    }



    public function getCourseSessionFromDB() {

        $courseSessions = DB::table('course_sessions')
            ->leftJoin('course_annuals', 'course_annuals.id', '=', 'course_sessions.course_annual_id')
            ->leftJoin('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
            ->select(
                'course_sessions.lecturer_id', 'course_sessions.time_course as time_course_session', 'course_sessions.time_td as time_td_session', 'course_sessions.time_tp as time_tp_session',
                'course_sessions.course_annual_id', 'course_sessions.id as course_session_id', 'course_annuals.department_id', 'course_annuals.degree_id', 'course_annuals.grade_id',
                'course_annuals.department_option_id', 'course_annuals.semester_id', 'course_annuals.academic_year_id',
                'course_annuals.name_en as name_en','course_annuals.name_kh', 'course_annuals.name_fr', 'degrees.code as degree_code'
            );

        return $courseSessions;
    }



    public function getNotSelectedCourseByDept($deptId, $academicYearId, $grade_id, $degree_id, $dept_option_id, $semester_id) {

        $courseSessions = $this->getCourseSessionFromDB();

        $courseSessions = $courseSessions->where('course_sessions.lecturer_id', '=', null);// this to get not assigned courses
//        $courseAnnuals = $courseAnnuals->where('course_annuals.employee_id', '=', null);// this to get not assigned courses


        if($deptId) {
            $courseSessions = $courseSessions->where('course_annuals.department_id', $deptId);
//            $courseAnnuals = $courseAnnuals->where('departments.id', '=',$deptId);
        }
        if($academicYearId) {
            $courseSessions = $courseSessions->where('course_annuals.academic_year_id', $academicYearId);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearId);
        }


        if($degree_id) {
            $courseSessions = $courseSessions->where('course_annuals.degree_id', $degree_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }

        if($grade_id) {
            $courseSessions = $courseSessions->where('course_annuals.grade_id', $grade_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }

        if($dept_option_id != 'null') {
            $courseSessions = $courseSessions->where('course_annuals.department_option_id', $dept_option_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$dept_option_id);
        }
        if($semester_id) {
            $courseSessions = $courseSessions->where('course_annuals.semester_id', $semester_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semester_id);
        }
        $courseSessions = $courseSessions->get();

        return $courseSessions;

    }

    public function allCourseByDepartment($request, $deptId) {


        $arrayCourses = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $dept_option_id = $request->department_option_id;
        $semester_id = $request->semester_id;

        $notSelectedCourses = $this->getNotSelectedCourseByDept($deptId, $academic_year_id, $grade_id, $degree_id, $dept_option_id, $semester_id);
        $groupFromDB = $this->getGroupBySessionAndAnnualCourse();


        if($notSelectedCourses) {

            foreach($notSelectedCourses as $course) {

                $totalCoursePerSemester = $course->time_tp_session + $course->time_td_session + $course->time_course_session;
                $splitName = explode('_', $course->name_en);
                $copy = $splitName[count($splitName)-1];

                $element = array(
                    "id" => 'department_'.$deptId.'_course_' . $course->course_session_id,
                    "text" => $course->name_en.' (S_'.$course->semester_id.' = '.$totalCoursePerSemester.')'. (isset($groupFromDB[$course->course_session_id])?' (Group:'.$this->formatGroupName($groupFromDB[$course->course_session_id]).')':''),
                    "li_attr" => [
                        'class' => 'department_course '.(($copy == '(copy)')?'current_copy':''),
                        'tp'    => $course->time_tp_session,
                        'td'    => $course->time_td_session,
                        'course' => $course->time_course_session,
                        'course_name' => $course->name_en
                    ],

                    'grade' => $course->grade_id,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false ]

                );

                array_push($arrayCourses, $element);
            }
        }

        return $arrayCourses;

    }

    public function getGroupBySessionAndAnnualCourse() {

        $array =[];
        $groups = DB::table('course_annual_classes')->get();

        foreach($groups as $group) {
            if($group->course_session_id != null) {
                $array[$group->course_session_id][] = $group;
            }
        }
        return ($array);

    }

    public function getAllteacherByDeptId ($deptID) {

        $allTeachers = DB::table('employees')
            ->select('employees.name_kh as teacher_name', 'employees.id as teacher_id', 'employees.department_id as department_id')
            ->where('employees.department_id', $deptID)
            ->orderBy('teacher_name')
            ->distinct('BINARY employees.name_kh')
            ->get();

        return $allTeachers;

    }


    public function get_department_tree($request) {

        $allDepartments= [];

        $deparmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $academicId = $request->academic_year_id;


        $depts = Department::where([
            ["parent_id",config('access.departments.department_academic')],
            ['departments.active', '=', true]
        ])
            ->select('departments.id as department_id', 'departments.name_en as department_name', 'departments.code as name_abr')
            ->orderBy('name_abr', 'ASC')
            ->get();

        foreach($depts as $dept) {

            if($deparmentId == $dept->department_id) {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => 'Department '.$dept->name_abr,
                    "children" => true,
                    "type" => "department",
                    "state" => ["opened" => true, "selected" => false ]
                );

                if($request->tree_side == 'teacher_annual') {

                    array_push($allDepartments, $element);

                } else if($request->tree_side == 'course_annual') {

                    return array($element);
                }

            } else {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => 'Department'.$dept->name_abr,
                    "children" => true,
                    "type" => "department",
                    "state" => ["opened" => false, "selected" => false ]

                );
                array_push($allDepartments, $element);
            }
        }

        return $allDepartments;
    }

    public function all_teacher_by_department($request, $department_id) {

        $teachers = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $semesters = Semester::get();

        $allTeachers = $this->getAllteacherByDeptId($department_id);
        $courseByTeacher = $this->courseSessionByTeacherFromDB($academic_year_id, $grade_id, $degree_id);

        foreach ($allTeachers as $teacher) {

            $selectedCourses = isset($courseByTeacher[$teacher->teacher_id])?$courseByTeacher[$teacher->teacher_id]:null;

            $totalCoursePerSemester=[];
            $timeTpS1 =0;
            $timeTpS2 =0;
            $timeTdS1 =0;
            $timeTdS2 =0;
            $timeCourseS1 =0;
            $timeCourseS2 =0;

            if($selectedCourses !=null) {
                foreach($selectedCourses as $course) {

                    $totalCoursePerSemester[$course->semester_id][]=$course->time_tp_session + $course->time_td_session + $course->time_course_session;

                    if($course->semester_id == SemesterEnum::SEMESTER_ONE) {

                        $timeTpS1 = $timeTpS1 +  $course->time_tp_session;
                        $timeTdS1 =$timeTdS1 + $course->time_td_session;
                        $timeCourseS1 = $timeCourseS1 + $course->time_course_session;

                    } else {
                        $timeTpS2 = $timeTpS2 +  $course->time_tp_session;
                        $timeTdS2 =$timeTdS2 + $course->time_td_session;
                        $timeCourseS2 = $timeCourseS2 + $course->time_course_session;
                    }

                }
            }

            if($teacher->department_id == $department_id) {
                $t_HourS1 = 0;
                $t_HourS2 = 0;

                if(isset($totalCoursePerSemester[SemesterEnum::SEMESTER_ONE])) {
                    foreach($totalCoursePerSemester[SemesterEnum::SEMESTER_ONE] as $S1_total) {
                        $t_HourS1 = $t_HourS1 + $S1_total;
                    }
                }
                if(isset($totalCoursePerSemester[SemesterEnum::SEMESTER_TWO])) {
                    foreach($totalCoursePerSemester[SemesterEnum::SEMESTER_TWO] as $S2_toal) {
                        $t_HourS2 = $t_HourS2 + $S2_toal;
                    }
                }

                $element = array(
                    "id"        => 'department_'.$department_id.'_teacher_' . $teacher->teacher_id,
                    "text"      => $teacher->teacher_name.' (S1 = '.$t_HourS1. ' | S2 = '.$t_HourS2.')',
                    "children"  => true,
                    "type"      => "teacher",
                    "state"     => ["opened" => true, "selected" => false ],
                    "li_attr"   => [
                        'class'         => 'teacher',
                        'time_tp'       => ' (S1 = '.$timeTpS1. ' | S2 = '.$timeTpS2.')',
                        'time_td'       => ' (S1 = '.$timeTdS1. ' | S2 = '.$timeTdS2.')',
                        'time_course'  => ' (S1 = '.$timeCourseS1. ' | S2 = '.$timeCourseS2.')'
                    ],

                );
                array_push($teachers, $element);

            } else {
                $element = array(
                    "id" => 'department_'.$department_id.'_teacher_' . $teacher->teacher_id,
                    "text" =>  $teacher->teacher_name.' (S1 = '.$t_HourS1. ' | S2 = '.$t_HourS2.')',
                    "children" => true,
                    "type" => "teacher",
                    "state" => ["opened" => false, "selected" => false ],
                    "li_attr" => [
                        'class' => 'teacher',
                        'time_tp'       => ' (S1 = '.$timeTpS1. ' | S2 = '.$timeTpS2.')',
                        'time_td'       => ' (S1 = '.$timeTdS1. ' | S2 = '.$timeTdS2.')',
                        'time_course'  => ' (S1 = '.$timeCourseS1. ' | S2 = '.$timeCourseS2.')'
                    ],

                );
                array_push($teachers, $element);
            }
        }


        return $teachers;
    }


    /*public function selected_course_by_teacher($request, $parent_id, $teacher_id) {

        $courses = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;

        $courseByTeacher = $this->courseSessionByTeacherFromDB($academic_year_id, $grade_id, $degree_id );
        $selectedCourses = isset($courseByTeacher[$teacher_id])?$courseByTeacher[$teacher_id]:null;
        $groupFromDB = $this->getGroupBySessionAndAnnualCourse();


        if($selectedCourses != null) {

            foreach($selectedCourses as $courseSession) {

//                (isset($groupFromDB[$courseSession->course_session_id])?', Group: '.$this->formatGroupName($groupFromDB[$courseSession->course_session_id]):'');


                $element = array(
                    "id" => $parent_id.'_course-session_' . $courseSession->course_session_id,
                    "text" => $courseSession->name_en.(isset($groupFromDB[$courseSession->course_session_id])?', Group: '.$this->formatGroupName($groupFromDB[$courseSession->course_session_id]):'').' ('.$courseSession->degree_code.$courseSession->grade_id.')',
                    "children" => false,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false ],
                    "li_attr" => [
                        'class' => 'teacher_course',
                    ],
                );
                array_push($courses, $element);

            }

            return Response::json($courses);
        }

    }*/


}