<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 5/5/17
 * Time: 1:45 PM
 */

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use App\Repositories\Backend\CourseSession\CourseSessionRepositoryContract;
use App\Repositories\Backend\CourseAnnualClass\CourseAnnualClassRepositoryContract;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

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

    public function generate_course_annual($request) {

        $courseAnnuals = DB::table('course_annuals')->where('course_annuals.academic_year_id', ($request->academic_year_id-1));

        $departmentId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $check =0;
        $unCheck=0;
        if($departmentId) {

            $courseAnnuals = $courseAnnuals->where('course_annuals.department_id', '=', $departmentId);
        }
        if($degreeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=', $degreeId);
        }
        if($gradeId) {
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
        foreach($courseAnnuals as $course) {

            $input = (array)$course;
            $input['academic_year_id'] = (int)$request->academic_year_id;

            // check for preventing a sencond time of generating Course Annual
            $isGenerated = $this->isCourseAnnualGenerated($course->course_id,$course->semester_id,$request->academic_year_id,$course->department_id, $course->degree_id,$course->grade_id, $course->employee_id);
            if($isGenerated) {
                $unCheck++;
                continue;
            } else {
                $store = $this->courseAnnuals->create($input);

                /*---create course session by course annual ---*/
                if(isset($courseSessions[$course->id])) {
                    /*--create new course session for the generated course annual---*/
                    foreach($courseSessions[$course->id] as $courseSession) {
                        $sessionInput = (array)$courseSession;
                        $sessionInput['course_annual_id'] = $store->id;
                        $saveCourseSession = $this->courseSessions->create($sessionInput);
                        /*---create course annual class by previous course session but change to the generated one ---*/

                        if(isset($courseAannualClassesBySession[$courseSession->id])) {

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
                if(isset($courseAannualClassesByAnnual[$course->id])) {
                    $json = json_encode($courseAannualClassesByAnnual[$course->id]);
                    $decode = json_decode($json, true);
                    $annualclassInput = [
                        'groups' => array_column($decode, 'group_id'),
                        'course_annual_id' => $store->id
                    ];
                    $saveCourseAnnualClassByAnnual = $this->courseAnnualClasses->create($annualclassInput);
                }

                if($store) {
                    $check++;
                }
            }
        }

        if($check == count($courseAnnuals) - $unCheck) {

            return ['status'=> true, 'message'=>'Course Annual Generated!!'];

        } else {
            return ['status'=> true, 'message'=>'Course Annual Not Generated!!'];
        }

    }

    public function isCourseAnnualGenerated($courseId, $semesterId, $academicYearId, $departmentId, $degreeId, $gradeId, $employeeId) {

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
        if($select) {
            return true;
        } else {
            return false;
        }

    }

    public function getAvailableCourse($deptId, $academicYearId, $semesterId) {

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

        if($deptId) {
            $availableCourses = $availableCourses->where('course_annuals.department_id', $deptId);
        }

        return $availableCourses;
    }

    public function dataSendToView($courseAnnualId) {

        $courseAnnual = DB::table('course_annuals')->where('id', $courseAnnualId)->first();

        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $availableCourses = $this->getAvailableCourse($dept=null, $courseAnnual->academic_year_id, $courseAnnual->semester_id);

        if(auth()->user()->allow("view-all-score-in-all-department") || auth()->user()->allow('view-all-score-course-annual')) {

            $availableCourses = $availableCourses->orderBy('course_annuals.id')->get();
        } else {
            if(auth()->user()->allow("input-score-course-annual")){ // only teacher in every department who have this permission

                $availableCourses = $availableCourses->where('employee_id', $employee->id)->orderBy('course_annuals.id')->get();

            } else {
                $availableCourses = $availableCourses->orderBy('course_annuals.id')->get();
            }
        }

        $selectedCourses =[];

        foreach($availableCourses as $availableCourse) {
            $selectedCourses[$availableCourse->course_annual_id][] = $availableCourse;
        }

        return [
            'course_annual' => $courseAnnual,
            'available_course'  =>$selectedCourses
        ];
    }

}