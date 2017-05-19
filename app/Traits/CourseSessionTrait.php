<?php
/**
 * Created by PhpStorm.
 * User: imac-04
 * Date: 4/26/17
 * Time: 1:53 PM
 */

namespace App\Traits;

use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\CourseSession;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use Illuminate\Support\Facades\Response;
use App\Models\Semester;
use App\Models\Enum\SemesterEnum;
use App\Models\CourseAnnualClass;

trait CourseSessionTrait
{


    public function courseSessionByTeacherFromDB($academicYearId, $grade_id, $degree_id)
    {

        $courseSessions = $this->course_session_by_teacher($academicYearId);
        $courseSessions = $courseSessions->where('course_annuals.academic_year_id', $academicYearId);
        if ($degree_id) {
            $courseSessions = $courseSessions->where('course_annuals.degree_id', '=', $degree_id);
        }
        if ($grade_id) {
            $courseSessions = $courseSessions->where('course_annuals.grade_id', '=', $grade_id);
        }
        $courseSessions = $courseSessions->get()->groupBy('lecturer_id');

        return $courseSessions;
    }

    public function course_session_by_teacher($academicYearId/*, $grade_id, $degree_id*/)
    {


        /*
        $activeUsers = DB::table('course_sessions')
            ->select('lecturer_id', DB::raw('count(*) as lecturer_ids'))
            ->groupBy('lecturer_id')
            ->orderBy('lecturer_ids', 'desc')
            ->get();
        $courseSession = collect(DB::table('course_sessions')->get())->groupBy('lecturer_id')->toArray();
        dd($courseSession);
        */

        $course_annuals = CourseAnnual::where('academic_year_id', $academicYearId)
            ->join('course_sessions', function ($query) {
                $query->on('course_annuals.id', '=', 'course_sessions.course_annual_id')
                    ->whereNotNull('course_sessions.lecturer_id');
            })
            ->join('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
            ->select($this->getCols());

        return $course_annuals;

    }

    public function getCols()
    {

        return ['course_sessions.lecturer_id', 'course_sessions.time_course as time_course_session', 'course_sessions.time_td as time_td_session', 'course_sessions.time_tp as time_tp_session',
            'course_sessions.course_annual_id', 'course_sessions.id as course_session_id', 'course_annuals.department_id', 'course_annuals.degree_id', 'course_annuals.grade_id',
            'course_annuals.department_option_id', 'course_annuals.semester_id', 'course_annuals.academic_year_id',
            'course_annuals.name_en as name_en', 'course_annuals.name_kh', 'course_annuals.name_fr', 'degrees.code as degree_code'];
    }

    public function getCourseSessionFromDB()
    {

        $courseSessions = DB::table('course_sessions')
            ->leftJoin('course_annuals', 'course_annuals.id', '=', 'course_sessions.course_annual_id')
            ->leftJoin('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
            ->select($this->getCols());

        return $courseSessions;
    }

    public function course_session_by_dept($dept_id)
    {

        /* $course_sessions = DB::table('course_sessions')
             ->join('course_annuals', 'course_annuals.id','=', 'course_sessions.course_annual_id')
             ->join('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
             ->where(function($query) use ($dept_id) {
                 $query->whereIn('course_sessions.course_annual_id', DB::table('course_annuals')->where('department_id', $dept_id)->lists('id'));
             })->select($this->getCols());*/

        $courseSessions = CourseAnnual::where('department_id', $dept_id)
            ->join('course_sessions', function ($query) {
                $query->on('course_annuals.id', '=', 'course_sessions.course_annual_id')
                    ->whereNull('course_sessions.lecturer_id');
            })
            ->join('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
            ->select($this->getCols());


        return $courseSessions;

    }

    public function getNotSelectedCourseByDept($deptId, $academicYearId, $grade_id, $degree_id, $dept_option_id, $semester_id)
    {

        if ($deptId) {
            $courseSessions = $this->course_session_by_dept($deptId);
        } else {
            $courseSessions = CourseAnnual::where('academic_year_id', $academicYearId)
                ->join('course_sessions', function ($query) {
                    $query->on('course_annuals.id', '=', 'course_sessions.course_annual_id')
                        ->whereNull('course_sessions.lecturer_id');
                })
                ->join('degrees', 'degrees.id', '=', 'course_annuals.degree_id')
                ->select($this->getCols());
        }

        $courseSessions = $courseSessions->where('course_sessions.lecturer_id', '=', null);// this to get not assigned courses
//        $courseAnnuals = $courseAnnuals->where('course_annuals.employee_id', '=', null);// this to get not assigned courses


        if ($deptId) {
            $courseSessions = $courseSessions->where('course_annuals.department_id', $deptId);
//            $courseAnnuals = $courseAnnuals->where('departments.id', '=',$deptId);
        }
        /*if($academicYearId) {
            $courseSessions = $courseSessions->where('course_annuals.academic_year_id', $academicYearId);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearId);
        }*/

        if ($degree_id) {
            $courseSessions = $courseSessions->where('course_annuals.degree_id', $degree_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }

        if ($grade_id) {
            $courseSessions = $courseSessions->where('course_annuals.grade_id', $grade_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }

        if ($dept_option_id != 'null') {
            $courseSessions = $courseSessions->where('course_annuals.department_option_id', $dept_option_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$dept_option_id);
        }
        if ($semester_id) {
            $courseSessions = $courseSessions->where('course_annuals.semester_id', $semester_id);
//            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semester_id);
        }
        $courseSessions = $courseSessions->get();


        return $courseSessions;

    }

    public function allCourseByDepartment($request, $deptId)
    {

        $arrayCourses = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $dept_option_id = $request->department_option_id;
        $semester_id = $request->semester_id;

        $notSelectedCourses = $this->getNotSelectedCourseByDept($deptId, $academic_year_id, $grade_id, $degree_id, $dept_option_id, $semester_id);
        $groupFromDB = $this->getGroupBySessionAndAnnualCourse();

        if ($notSelectedCourses) {

            foreach ($notSelectedCourses as $course) {

                $totalCoursePerSemester = $course->time_tp_session + $course->time_td_session + $course->time_course_session;
                $splitName = explode('_', $course->name_en);
                $copy = $splitName[count($splitName) - 1];

                $element = array(
                    "id" => 'department_' . $deptId . '_course_' . $course->course_session_id,
                    "text" => $course->name_en . ' (S_' . $course->semester_id . ' = ' . $totalCoursePerSemester . ')' . (isset($groupFromDB[$course->course_session_id]) ? ' (Group:' . $this->formatGroupName($groupFromDB[$course->course_session_id]) . ')' : ''),
                    "li_attr" => [
                        'class' => 'department_course ' . (($copy == '(copy)') ? 'current_copy' : ''),
                        'tp' => $course->time_tp_session,
                        'td' => $course->time_td_session,
                        'course' => $course->time_course_session,
                        'course_name' => $course->name_en
                    ],

                    'grade' => $course->grade_id,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false]

                );

                array_push($arrayCourses, $element);
            }
        }

        return $arrayCourses;

    }

    public function getGroupBySessionAndAnnualCourse()
    {

        $groups = CourseAnnualClass::all()
            ->whereIn('course_session_id', DB::table('course_sessions')->lists('id'))
            ->groupBy('course_session_id', true)
            ->toArray();//keyBy('course_session_id')->
        return ($groups);

    }

    public function course_annual_class_group()
    {

    }

    public function getAllteacherByDeptId($deptID)
    {

        $allTeachers = DB::table('employees')
            ->select('employees.name_kh as teacher_name', 'employees.id as teacher_id', 'employees.department_id as department_id')
            ->where('employees.department_id', $deptID)
            ->orderBy('teacher_name')
            ->distinct('BINARY employees.name_kh')
            ->get();

        return $allTeachers;

    }

    public function get_department_tree($request)
    {

        $allDepartments = [];

        $deparmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $academicId = $request->academic_year_id;


        $depts = Department::where([
            ["parent_id", config('access.departments.department_academic')],
            ['departments.active', '=', true]
        ])
            ->select('departments.id as department_id', 'departments.name_en as department_name', 'departments.code as name_abr')
            ->orderBy('name_abr', 'ASC')
            ->get();

        foreach ($depts as $dept) {

            if ($deparmentId == $dept->department_id) {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => 'Department ' . $dept->name_abr,
                    "children" => true,
                    "type" => "department",
                    "state" => ["opened" => true, "selected" => false]
                );

                if ($request->tree_side == 'teacher_annual') {

                    array_push($allDepartments, $element);

                } else if ($request->tree_side == 'course_annual') {

                    return array($element);
                }

            } else {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => 'Department' . $dept->name_abr,
                    "children" => true,
                    "type" => "department",
                    "state" => ["opened" => false, "selected" => false]

                );
                array_push($allDepartments, $element);
            }
        }

        return $allDepartments;
    }

    public function all_teacher_by_department($request, $department_id)
    {

        $teachers = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $semesters = Semester::get();

        $allTeachers = $this->getAllteacherByDeptId($department_id);
        $courseByTeacher = $this->courseSessionByTeacherFromDB($academic_year_id, $grade_id, $degree_id);

        foreach ($allTeachers as $teacher) {

            $selectedCourses = isset($courseByTeacher[$teacher->teacher_id]) ? $courseByTeacher[$teacher->teacher_id] : null;

            $totalCoursePerSemester = [];
            $timeTpS1 = 0;
            $timeTpS2 = 0;
            $timeTdS1 = 0;
            $timeTdS2 = 0;
            $timeCourseS1 = 0;
            $timeCourseS2 = 0;

            if ($selectedCourses != null) {
                foreach ($selectedCourses as $course) {

                    $totalCoursePerSemester[$course->semester_id][] = $course->time_tp_session + $course->time_td_session + $course->time_course_session;

                    if ($course->semester_id == SemesterEnum::SEMESTER_ONE) {

                        $timeTpS1 = $timeTpS1 + $course->time_tp_session;
                        $timeTdS1 = $timeTdS1 + $course->time_td_session;
                        $timeCourseS1 = $timeCourseS1 + $course->time_course_session;

                    } else {
                        $timeTpS2 = $timeTpS2 + $course->time_tp_session;
                        $timeTdS2 = $timeTdS2 + $course->time_td_session;
                        $timeCourseS2 = $timeCourseS2 + $course->time_course_session;
                    }

                }
            }

            if ($teacher->department_id == $department_id) {
                $t_HourS1 = 0;
                $t_HourS2 = 0;

                if (isset($totalCoursePerSemester[SemesterEnum::SEMESTER_ONE])) {
                    foreach ($totalCoursePerSemester[SemesterEnum::SEMESTER_ONE] as $S1_total) {
                        $t_HourS1 = $t_HourS1 + $S1_total;
                    }
                }
                if (isset($totalCoursePerSemester[SemesterEnum::SEMESTER_TWO])) {
                    foreach ($totalCoursePerSemester[SemesterEnum::SEMESTER_TWO] as $S2_toal) {
                        $t_HourS2 = $t_HourS2 + $S2_toal;
                    }
                }

                $element = array(
                    "id" => 'department_' . $department_id . '_teacher_' . $teacher->teacher_id,
                    "text" => $teacher->teacher_name . ' (S1 = ' . $t_HourS1 . ' | S2 = ' . $t_HourS2 . ')',
                    "children" => true,
                    "type" => "teacher",
                    "state" => ["opened" => true, "selected" => false],
                    "li_attr" => [
                        'class' => 'teacher',
                        'time_tp' => ' (S1 = ' . $timeTpS1 . ' | S2 = ' . $timeTpS2 . ')',
                        'time_td' => ' (S1 = ' . $timeTdS1 . ' | S2 = ' . $timeTdS2 . ')',
                        'time_course' => ' (S1 = ' . $timeCourseS1 . ' | S2 = ' . $timeCourseS2 . ')'
                    ],

                );
                array_push($teachers, $element);

            } else {
                $element = array(
                    "id" => 'department_' . $department_id . '_teacher_' . $teacher->teacher_id,
                    "text" => $teacher->teacher_name . ' (S1 = ' . $t_HourS1 . ' | S2 = ' . $t_HourS2 . ')',
                    "children" => true,
                    "type" => "teacher",
                    "state" => ["opened" => false, "selected" => false],
                    "li_attr" => [
                        'class' => 'teacher',
                        'time_tp' => ' (S1 = ' . $timeTpS1 . ' | S2 = ' . $timeTpS2 . ')',
                        'time_td' => ' (S1 = ' . $timeTdS1 . ' | S2 = ' . $timeTdS2 . ')',
                        'time_course' => ' (S1 = ' . $timeCourseS1 . ' | S2 = ' . $timeCourseS2 . ')'
                    ],

                );
                array_push($teachers, $element);
            }
        }


        return $teachers;
    }

    public function selected_course_by_teacher($request, $parent_id, $teacher_id)
    {

        $courses = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;

        $courseByTeacher = $this->courseSessionByTeacherFromDB($academic_year_id, $grade_id, $degree_id);
        $selectedCourses = isset($courseByTeacher[$teacher_id]) ? $courseByTeacher[$teacher_id] : null;
        $groupFromDB = $this->getGroupBySessionAndAnnualCourse();

        if ($selectedCourses != null) {

            foreach ($selectedCourses as $courseSession) {
//                (isset($groupFromDB[$courseSession->course_session_id])?', Group: '.$this->formatGroupName($groupFromDB[$courseSession->course_session_id]):'');

                $element = array(
                    "id" => $parent_id . '_course-session_' . $courseSession->course_session_id,
                    "text" => $courseSession->name_en . (isset($groupFromDB[$courseSession->course_session_id]) ? ', Group: ' . $this->formatGroupName($groupFromDB[$courseSession->course_session_id]) : '') . ' (' . $courseSession->degree_code . $courseSession->grade_id . ')',
                    "children" => false,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false],
                    "li_attr" => [
                        'class' => 'teacher_course',
                    ],
                );
                array_push($courses, $element);
            }
            return $courses;
        } else {
            return [];
        }

    }

    public function group_student_by_department($request, $nodeId)
    {

        $arrayGroup = [];
        $deptId = $nodeId[count($nodeId) - 1];
        $groups = $this->getStudentGroupFromDB();
        $groups = $groups->where('studentAnnuals.department_id', '=', $deptId);

        if ($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=', $academicYearId);
        }
//        if($semesterId = $request->semester_id) {
//            $groups = $groups->where('studentAnnuals.semester_id', '=',$semesterId);
//        }
        if ($gradeId = $request->grade_id) {
            $groups = $groups->where('studentAnnuals.grade_id', '=', $gradeId);
        }
        if ($degreeId = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=', $degreeId);
        }
        if ($deptOptionId = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=', $degreeId);
        }
        $groups = $groups->get();

        usort($groups, function ($a, $b) {
            return $a->group - $b->group;
        });

        if (count($groups) > 0) {
            foreach ($groups as $group) {
                //echo($group->group);
                if ($group->group_id != null) {

                    $element = [
                        'id' => 'department_' . $deptId . '_' . $group->group_code,
                        'text' => (($degreeId == 1) ? 'I' : 'T ') . $gradeId . '_' . $group->group_code,
                        'li_attr' => [
                            'class' => 'student_group'
                        ],
                        "type" => "group",
                        "state" => ["opened" => false, "selected" => false]

                    ];
                    $arrayGroup[] = $element;
                }
            }
        }
        return $arrayGroup;
    }

    public function getStudentGroupFromDB()
    {

        $groups = DB::table('groups')
            ->join('studentAnnuals', function ($query) {
                $query->on('groups.id', '=', 'studentAnnuals.group_id');
            })
            ->select('groups.id as group_id', 'groups.code as group_code')
            ->groupBy('groups.id')
            ->orderBy('group_code');

        return $groups;
    }

    public function deptHasOption($deptId)
    {

        $dept = Department::find($deptId);
        if ($dept->department_options) {
            $deptOptions = $dept->department_options->lists('name_en', 'id');
        } else {
            $deptOptions = [];
        }
        return $deptOptions;
    }

    public function remove_course($input)
    {

        $nodeID = json_decode($input);

        if (count($nodeID) > 0) {
            $check = 0;
            $uncount = 0;

            foreach ($nodeID as $id) {
                $explode = explode('_', $id);
                if (count($explode) > 4) {// the node id here will return the above node so we want to delete only the depest node (course) then the id of each course length must greater than 4
//                    $deparment_id = $explode[1];
//                    $teacher_id =  $explode[3];
                    $course_session_id = $explode[5];
                    $update = $this->updateCourse($course_session_id, $inputs = '');

                    if ($update) {
                        $check++;
                    }

                } else {
                    $uncount++;
                }
            }
        }
        if ($check == (count($nodeID) - $uncount)) {
            return true;

        } else {
            return false;
        }
    }

    public function formatGroupName($listsGroup)
    {

        $name = '';

        foreach ($listsGroup as $group) {
            $name = $name . ' ' . $group['group_id'];
        }
        return $name;
    }

    public function assign_course($request)
    {

        $arrayCourseId = $request->course_id;
        $arrayTeacherId = $request->teacher_id;
        $check = 0;
        $uncount = 0;
        $index = 0;

        if (count($arrayTeacherId) > 0) {

            if (count($arrayCourseId) > 0) {


                foreach ($arrayTeacherId as $teacher) {

                    $teacherId = explode('_', $teacher);

                    if (count($teacherId) == 4) {
                        $lecturer_id = $teacherId[3];

                        foreach ($arrayCourseId as $course) {

                            $courseId = explode('_', $course);

                            if (count($courseId) == 4) {

                                $course_session_id = $courseId[3];

                                $input = [
                                    'active' => true,
                                    'lecturer_id' => $lecturer_id,
                                ];
                                $res = $this->updateCourse($course_session_id, $input);

                                if ($res) {
                                    $check++;
                                }

                            } else {
                                $index++;
                            }
                        }

                    } else {
                        $uncount++;
                    }
                }

                if ((count($arrayTeacherId) - $uncount) != 0) {
                    if ($check == (count($arrayCourseId) - $index) * (count($arrayTeacherId) - $uncount)) {

                        return ['status' => true, 'message' => 'Course Added'];

                    } else {
                        return ['status' => false, 'message' => 'Course Not Added!!'];
                    }
                } else {
                    return ['status' => false, 'message' => 'Teacher Not Selected!!'];
                }

            } else {
                return ['status' => false, 'message' => 'Not Selected Course!!'];
            }

        } else {
            return ['status' => false, 'message' => 'Not Seleted Teacher!'];
        }

    }


    public function updateCourse($courseSessionId, $input)
    {

        $courseSession = CourseSession::where('id', $courseSessionId)->first();
        $courseSession->lecturer_id = isset($input['lecturer_id']) ? $input['lecturer_id'] : null;
        $courseSession->write_uid = auth()->id();
        if ($courseSession->update()) {
            $this->timetableSlots->update_course_session($courseSession);
            return true;
        } else {
            return false;
        }

    }

    public function selectedGroupByCourseSession($array_course_session_ids)
    {

        $arrayGroups = [];
        $selectedGroups = collect(
            DB::table('course_annual_classes')
                ->where('course_annual_id', null)
                ->whereNotNull('group_id')
                ->whereIn('course_session_id', $array_course_session_ids)
                ->select('course_session_id', 'group_id')
                ->get()
        )->groupBy('course_session_id');

        $groups = $selectedGroups->toArray();
        foreach ($array_course_session_ids as $annual_id) {
            if (isset($groups[$annual_id])) {
                $arrayGroups[$annual_id] = array_column(json_decode(json_encode($groups[$annual_id]), true), 'group_id');
            }
        }
        return $arrayGroups;

    }

    public function duplicate_couse_session($request)
    {


        $explode = explode('_', $request->dept_course_id);
        $courseAnnualId = $explode[3];
        $couseSesssionId = $explode[3];
        $courseSession = DB::table('course_sessions')->where('id', $couseSesssionId)->first();

        $courseAnnualClasses = DB::table('course_annual_classes')
            ->where('course_session_id', $couseSesssionId)
            ->whereNull('course_annual_id')
            ->lists('group_id');


        $inputs = [
            'time_course' => $courseSession->time_course,
            'time_td' => $courseSession->time_td,
            'time_tp' => $courseSession->time_tp,
            'course_annual_id' => $courseSession->course_annual_id
        ];
        $save = $this->courseSessions->create($inputs);
        if ($save) {

            $data = [
                'groups' => $courseAnnualClasses,
                'course_annual_id' => null,
                'course_session_id' => $save->id
            ];

            $saveCourseAnnualClass = $this->courseAnnualClasses->create($data);

            if ($saveCourseAnnualClass) {
                return ['status' => true, 'message' => 'Course Duplicated!'];
            } else {
                return ['status' => false, 'message' => 'Error Duplicated!'];
            }
        }
    }


    public function edit_course_session($request)
    {

        $explode = explode('_', $request->dept_course_id);// department with course session id concatination
        $courseSessionId = $explode[3];
        $deptId = $explode[1];

        $course = DB::table('course_sessions')->where('id', $courseSessionId)->first();
        $courseAnnual = DB::table('course_annuals')->where('id', $course->course_annual_id)->first();
//        $selected_groups = $this->selectedGroupByCourseAnnual([$courseAnnual->id]);/*--Student Score Trait--*/

        $selected_groups = $this->selectedGroupByCourseSession([$course->id]); /*--Course Session Trait--*/


        $group_by_course_annual = DB::table('course_annual_classes')
            ->whereNull('course_session_id')
            ->where('course_annual_id', $course->course_annual_id)
            ->join('groups', 'groups.id', '=', 'course_annual_classes.group_id')
            ->select('groups.id as group_id', 'groups.code as group_code')
            ->orderBy('groups.code')->get();


        usort($group_by_course_annual, function ($a, $b) {
            return $a->group_code - $b->group_code;
        });
        if ($courseAnnual) {
            return view('backend.course.courseAnnual.includes.popup_edit_course_annual', compact('group_by_course_annual', 'course', 'selected_groups'));
        }
    }

    public function update_course_session($course_session_id, $request)
    {
        $groups = $request->group;
        $courseSession = DB::table('course_sessions')->where('id', $course_session_id)->first();
        $courseAnnual = DB::table('course_annuals')->where('id', $courseSession->course_annual_id)->first();
        $inputs = [
            'time_course' => $request->time_course,
            'time_td' => $request->time_td,
            'time_tp' => $request->time_tp,
        ];

        $update = $this->courseSessions->update($course_session_id, $inputs);
        if ($update) {
            $this->timetableSlots->update_course_session($update);
            $delete = DB::table('course_annual_classes')->where([
                ['course_annual_id', null],
                ['course_session_id', $courseSession->id],
            ]);

            $data = [
                'groups' => $groups,
                'course_annual_id' => null,
                'course_session_id' => $update->id
            ];
            if (count($delete->get()) > 0) {
                $delete = $delete->delete();
                if ($delete) {
                    $create = $this->courseAnnualClasses->create($data);
                }
            } else {
                $create = $this->courseAnnualClasses->create($data);
            }

            if ($create) {
                return ['status' => true, 'message' => 'update course successfully!', 'selected_element' => 'department_' . $courseAnnual->department_id . '_course_' . $courseAnnual->id];
            }

        } else {
            return ['status' => false, 'message' => 'Error Updating!!'];
        }
    }

    public function delete_course_session($request)
    {

        $explode = explode('_', $request->dept_course_id);
        $courseSessionId = $explode[3];
        $courseSession = DB::table('course_sessions')->where('id', $courseSessionId)->first();
        $courseAnnualClasses = DB::table('course_annual_classes')->where([
            ['course_annual_id', null],
            ['course_session_id', $courseSession->id]
        ])->delete();
        if ($courseAnnualClasses) {
            $delete = $this->courseSessions->destroy($courseSessionId);
            if ($delete) {
                return ['status' => true, 'message' => 'Successfully Deleted!'];
            } else {
                return ['status' => true, 'message' => 'Error Deleted!'];
            }
        }
    }


}