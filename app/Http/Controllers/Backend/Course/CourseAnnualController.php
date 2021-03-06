<?php

namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Backend\Course\CourseHelperTrait\GenerateStudentTrait;
use App\Http\Controllers\Backend\Course\CourseHelperTrait\ProficencyScoreTrait;
use App\Http\Controllers\Backend\Course\CourseHelperTrait\StudentStatisticTrait;
use App\Http\Controllers\Backend\StudentTrait\AverageFinalYearTrait;
use App\Http\Controllers\Backend\StudentTrait\PrintAttestationTrait;
use App\Http\Controllers\Backend\StudentTrait\PrintTranscriptTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseAnnual\CourseAnnualAssignmentRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\CreateCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\DeleteCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\EditCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\ImportCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\StoreCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\ToggleScoringCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\UpdateCourseAnnualRequest;
use App\Models\Absence;
use App\Models\AcademicYear;
use App\Models\Average;
use App\Models\CompetencyType;
use App\Models\Configuration;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Enum\CourseAnnualEnum;
use App\Models\Enum\ScoreEnum;
use App\Models\Enum\SemesterEnum;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAnnual;
use App\Repositories\Backend\Absence\AbsenceRepositoryContract;
use App\Repositories\Backend\Average\AverageRepositoryContract;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use App\Repositories\Backend\CourseAnnualClass\CourseAnnualClassRepositoryContract;
use App\Repositories\Backend\CourseAnnualScore\CourseAnnualScoreRepositoryContract;
use App\Repositories\Backend\CourseSession\CourseSessionRepositoryContract;
use App\Repositories\Backend\Percentage\PercentageRepositoryContract;
use App\Repositories\Backend\ResitStudentAnnual\ResitStudentAnnualRepositoryContract;
use App\Traits\CourseAnnualTrait;
use App\Traits\CourseSessionTrait;
use App\Traits\ScoreProp;
use App\Traits\StudentScore;
use App\Traits\StudentTrait;
use App\Utils\DateTimeManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Utils\ResponseUtil;
use Maatwebsite\Excel\Facades\Excel;
use Response;


class CourseAnnualController extends Controller
{
    use StudentScore;
    use ScoreProp;
    use StudentTrait;
    use CourseSessionTrait;
    use CourseAnnualTrait;
    use StudentStatisticTrait;
    use ProficencyScoreTrait;
    use GenerateStudentTrait;
    use PrintTranscriptTrait;
    use PrintAttestationTrait;
    use AverageFinalYearTrait;

    /**
     * @var CourseAnnualRepositoryContract
     */
    protected $courseAnnuals;
    protected $courseAnnualScores;
    protected $percentages;
    protected $absences;
    protected $averages;
    protected $courseAnnualClasses;
    protected $courseSessions;
    protected $resitStudentAannuals;

    /**
     * @param CourseAnnualRepositoryContract $courseAnnualRepo
     */
    public function __construct(
        CourseAnnualRepositoryContract $courseAnnualRepo,
        CourseAnnualScoreRepositoryContract $courseAnnualScoreRepo,
        PercentageRepositoryContract $percentageRepo,
        AbsenceRepositoryContract $absenceRepo,
        AverageRepositoryContract $averageRepo,
        CourseAnnualClassRepositoryContract $courseAnnualClassRepo,
        CourseSessionRepositoryContract $courseSessionRepo,
        ResitStudentAnnualRepositoryContract $resitStudentRepo

    )
    {
        $this->courseAnnuals = $courseAnnualRepo;
        $this->courseAnnualScores = $courseAnnualScoreRepo;
        $this->percentages = $percentageRepo;
        $this->absences = $absenceRepo;
        $this->averages = $averageRepo;
        $this->courseAnnualClasses = $courseAnnualClassRepo;
        $this->courseSessions = $courseSessionRepo;
        $this->resitStudentAannuals = $resitStudentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");

        if (auth()->user()->allow("view-all-score-in-all-department")) {
            // Get all department in case user have privilege to view all department
            // In here, there is no limit (equal to admin privilege)
            $department_id = null;
            $lecturers = Employee::select("name_kh", "id", "name_latin", "id_card")->get();
            $options = DepartmentOption::get();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $department_id = $employee->department->id;
            $options = DepartmentOption::where('department_id', $employee->department_id)->get();
            if (auth()->user()->allow("view-all-score-course-annual")) {
                // This is chef department, he can see all courses in his department
                //$lecturers = Employee::where('department_id',$department_id)->lists("name_kh","id");
                $lecturers = CourseAnnual::join("employees", "course_annuals.employee_id", "=", "employees.id")
                    ->where("course_annuals.department_id", $department_id)
                    ->orWhere("course_annuals.responsible_department_id", $department_id)
                    ->select([
                        "course_annuals.employee_id as id",
                        "employees.name_kh as name_kh",
                        "employees.name_latin as name_latin",
                        "employees.id_card as id_card",
                    ])
                    ->groupBy([
                        'course_annuals.employee_id',
                        "employees.name_kh",
                        "employees.name_latin",
                        "employees.id_card"
                    ])
                    ->get();

            } else {
                $lecturers = null;
            }
        }

        $academicYears = AcademicYear::orderBy("id", "desc")->lists('name_latin', 'id');
        $degrees = Degree::lists('name_en', 'id');
        $grades = Grade::orderBy('id')->lists('name_en', 'id');
        $semesters = Semester::orderBy('id')->lists('name_en', 'id');
        $studentGroup = StudentAnnual::select('group')->groupBy('group')->orderBy('group')->lists('group');

        return view('backend.course.courseAnnual.index', compact('departments', 'academicYears', 'degrees', 'grades', 'semesters', 'studentGroup', 'department_id', 'lecturers', 'options'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * this function is used for filtering department option by the department id (return view as the selection option)
     */
    public function getDeptOption(Request $request)
    {

        $deptOptions = $this->deptHasOption($request->department_id);
        return view('backend.course.courseAnnual.includes.dept_option_selection', compact('deptOptions'));

    }

    /**
     * @param Request $request
     * @return mixed
     * this is a function used for returning group of students when creating each course annual (filter by course program id and
     *  - academic_year_id, grade_id, degree_id, ....)
     */
    public function filteringStudentGroup(Request $request)
    {

        $groups = $this->groupStudentAnnual($request->semester_id);
        $department = Department::where('id', $request->department_id)->first();

        if ($request->course_program_id) {

            $selectedGroups = DB::table('course_annuals')
                ->where(function ($query) use ($request) {
                    $query->where('course_annuals.course_id', $request->course_program_id);
                })
                ->join('course_annual_classes', function ($courseQuery) {
                    $courseQuery->on('course_annuals.id', '=', 'course_annual_classes.course_annual_id');
                })
                ->join('groups', function ($groupQuery) {
                    $groupQuery->on('groups.id', '=', 'course_annual_classes.group_id');
                });
            if ($request->academic_year_id) {
                $selectedGroups = $selectedGroups->where('course_annuals.academic_year_id', '=', $request->academic_year_id);
            }
            $selectedGroups = $selectedGroups->lists('groups.code');
        }

        if ($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=', $academicYearId);
        }
        if ($grade_id = $request->grade_id) {

            $groups = $groups->where('studentAnnuals.grade_id', '=', $grade_id);
        }
        if ($degree_id = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=', $degree_id);
        }
        if ($option_id = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.department_option_id', '=', $option_id);
        }

        if ($department->is_vocational) {

            $groups = $groups->where('group_student_annuals.department_id', $department->id);
        } else {

            if ($deptId = $request->department_id) {
                $groups = $groups->where('studentAnnuals.department_id', '=', $deptId);
                $groups = $groups->whereNull('group_student_annuals.department_id');
            }
        }


        $groupCodes = $groups->lists('group_code');
        $groupIdCodes = $groups->lists('group_id', 'group_code');

        asort($groupCodes);
        $array_group = array_values($groupCodes);


        if ($request->course_program_id) {
            if ($request->_method == CourseAnnualEnum::CREATE) {

                $not_selected_groups = array_diff($array_group, $selectedGroups);
                return Response::json(['group_code' => $not_selected_groups, 'group_id' => $groupIdCodes]);
            } else {

                return Response::json(['group_code' => $array_group, 'group_id' => $groupIdCodes]);
                //return Response::json($array_group);
            }
        } else {
            return Response::json(['group_code' => $array_group, 'group_id' => $groupIdCodes]);
            //return Response::json($array_group);
        }

    }

    /**
     * @param Request $request
     * @return string
     * this function will return groups of student as a view (selection)
     * @throws \Throwable
     */
    public function getStudentGroupSelection(Request $request)
    {

        $groups = $this->getStudentGroupFromDB();

        if ($deptId = $request->department_id) {
            $groups = $groups->where('studentAnnuals.department_id', '=', $deptId);
        }
        if ($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=', $academicYearId);
        }
        if ($degree_id = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=', $degree_id);
        }
        if ($grade_id = $request->grade_id) {
            $groups = $groups->where('studentAnnuals.grade_id', '=', $grade_id);
        }
        if ($option_id = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.department_option_id', '=', $option_id);
        }
        $groups = $groups->lists('group_code');

        return view('backend.course.courseAnnual.includes.student_group_selection', compact('groups'))->render();

    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseAnnualRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseAnnualRequest $request)
    {
        $other_departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
        if (auth()->user()->allow("view-all-score-in-all-department")) {
            $courses = Course::orderBy('updated_at', 'desc')->get();
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $department_id = null;
            $options = DepartmentOption::get();

            $raw_courses = Course::join('degrees', 'degrees.id', '=', 'courses.degree_id')
                ->join('departments', 'departments.id', '=', 'courses.department_id')
                ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'courses.department_option_id')
                ->select([
                    'courses.id',
                    'courses.name_en',
                    'courses.name_fr',
                    'courses.name_kh',
                    'courses.time_course',
                    'courses.time_tp',
                    'courses.time_td',
                    'courses.credit',
                    'courses.department_id',
                    'courses.degree_id',
                    'courses.grade_id',
                    'courses.department_option_id',
                    'courses.semester_id',
                    'courses.responsible_department_id',
                    'degrees.code as degree_code',
                    'departments.code as department_code',
                    'departmentOptions.code as option'
                ])
                ->orderBy('courses.degree_id', 'asc')
                ->orderBy('courses.grade_id', 'asc')
                ->orderBy('courses.semester_id', 'asc')
                ->where('courses.active', true)
                ->get();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $department_id = $employee->department->id;
            $options = DepartmentOption::where('department_id', $employee->department_id)->get();

            $raw_courses = Course::where('courses.department_id', $department_id)
                ->join('degrees', 'degrees.id', '=', 'courses.degree_id')
                ->join('departments', 'departments.id', '=', 'courses.department_id')
                ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'courses.department_option_id')
                ->select([
                    'courses.id',
                    'courses.name_en',
                    'courses.name_fr',
                    'courses.name_kh',
                    'courses.time_course',
                    'courses.time_tp',
                    'courses.time_td',
                    'courses.credit',
                    'courses.department_id',
                    'courses.degree_id',
                    'courses.grade_id',
                    'courses.department_option_id',
                    'courses.semester_id',
                    'degrees.code as degree_code',
                    'departments.code as department_code',
                    'departmentOptions.code as option'
                ])
                ->orderBy('courses.degree_id', 'asc')
                ->orderBy('courses.grade_id', 'asc')
                ->orderBy('courses.semester_id', 'asc')
                ->where('courses.active', true)
                ->get();
        }
        $courses = [];

        foreach ($raw_courses as $raw_course) {
            if (!isset($courses[$raw_course->department_code])) {
                $courses[$raw_course->department_code] = array();
            }
            array_push($courses[$raw_course->department_code], $raw_course);
        }

        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin', 'id')->toArray();
        $degrees = Degree::lists('name_kh', 'id')->toArray();
        $grades = Grade::lists('name_kh', 'id')->toArray();
        $semesters = Semester::lists("name_kh", "id");
        $competency_types = CompetencyType::lists('name', 'id')->toArray();
        return view('backend.course.courseAnnual.create', compact('competency_types', 'departments', 'academicYears', 'degrees', 'grades', 'courses', "semesters", 'options', 'other_departments'));
    }

    public function getDepts()
    {

        if (auth()->user()->allow("view-all-score-in-all-department")) {
            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = Department::where("parent_id", config('access.departments.department_academic'))
                ->whereNotIn('id', [$employee->department->id])
                ->orderBy("code")->lists("code", "id");
        }
        return view('backend.course.courseAnnual.includes.other_dept_selection', compact('departments'));
    }

    public function getOtherLecturer(Request $request)
    {

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $currentTeachersInHisDept = $this->getAllteacherByDeptId($employee->department->id);
        if ($request->department_id) {
            $teacherByDept = $this->getAllteacherByDeptId($request->department_id);
        } else {
            $teacherByDept = [];
        }

        $totalTeachers = array_merge($currentTeachersInHisDept, $teacherByDept);

        return view('backend.course.courseAnnual.includes.mix_teacher_selection', compact('totalTeachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseAnnualRequest $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreCourseAnnualRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $storeCourseAnnual = $this->courseAnnuals->create($data);
            if ($storeCourseAnnual instanceof CourseAnnual) {
                $data = $data + ['course_annual_id' => $storeCourseAnnual->id];
                $storeCourseAnnualClass = $this->courseAnnualClasses->create($data);
                // Only normal scoring (midterm+final) need to generate empty score
                if (isset($data["normal_scoring"]) and $data["normal_scoring"] == "checked") {
                    //----create score percentage ----
                    $this->createScorePercentage($request->midterm_score, $request->final_score, $storeCourseAnnual->id);
                }
                if ($storeCourseAnnualClass) {
                    DB::commit();
                    return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
                }
            }
            DB::rollback();
            return redirect()->back()->withFlashSuccess('Create Error!');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->withFlashSuccess($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditCourseAnnualRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCourseAnnualRequest $request, $id)
    {
        $groups = [];
        // $courseAnnual = $this->courseAnnuals->findOrThrowException($id);
        $courseAnnual = CourseAnnual::with('reference_course')->find($id);
        $ownerCourseDeparment = Department::where('id', $courseAnnual->department_id)->first();
        $scores = $this->getPropertiesFromScoreTable($courseAnnual);
        $arrayPercentages = collect($scores)->groupBy('percentage_id')->toArray();

        foreach ($arrayPercentages as $key => $percentage) {

            $explode = explode('-', $percentage[0]->name);
            if (strtolower($explode[0]) == strtolower(ScoreEnum::Name_Mid)) {
                $midterm['percentage'] = $percentage[0]->percent;
                $midterm['percentage_id'] = $key;
            }

            if (strtolower($explode[0]) == strtolower(ScoreEnum::Name_Fin)) {
                //----find midterm from final value
                $final['percentage'] = $percentage[0]->percent;
                $final['percentage_id'] = $key;
            }
        }

        $array_groups = $this->groupStudentAnnual($courseAnnual->semester_id);
        if ($ownerCourseDeparment->is_vocational) {

            $array_groups = $array_groups
                ->where('studentAnnuals.academic_year_id', '=', $courseAnnual->academic_year_id)
                ->where('studentAnnuals.degree_id', '=', $courseAnnual->degree_id)
                ->where('studentAnnuals.grade_id', '=', $courseAnnual->grade_id)
                ->where('studentAnnuals.department_option_id', '=', $courseAnnual->department_option_id)
                ->where('group_student_annuals.department_id', '=', $courseAnnual->department_id)
                ->orderBy('group_code')
                ->get();

        } else {

            $array_groups = $array_groups
                ->where('studentAnnuals.department_id', '=', $courseAnnual->department_id)
                ->where('studentAnnuals.academic_year_id', '=', $courseAnnual->academic_year_id)
                ->where('studentAnnuals.degree_id', '=', $courseAnnual->degree_id)
                ->where('studentAnnuals.grade_id', '=', $courseAnnual->grade_id)
                ->where('studentAnnuals.department_option_id', '=', $courseAnnual->department_option_id)
                ->whereNull('group_student_annuals.department_id')
                ->orderBy('group_code')
                ->get();
        }

        usort($array_groups, function ($a, $b) {
            if ($a != null & $b != null) {
                if (is_numeric($a->group_code)) {
                    return $a->group_code - $b->group_code;
                } else {
                    return strcmp($a->group_code, $b->group_code);
                }
            } else {
                return "";
            }
        });

        $groups = $array_groups;

        $other_departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
        if (auth()->user()->allow("view-all-score-in-all-department")) {
            //$courses = Course::orderBy('updated_at', 'desc')->get();
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $department_id = null;
            $options = DepartmentOption::get();

            $raw_courses = Course::join('degrees', 'degrees.id', '=', 'courses.degree_id')
                ->join('departments', 'departments.id', '=', 'courses.department_id')
                ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'courses.department_option_id')
                ->select([
                    'courses.id',
                    'courses.name_en',
                    'courses.name_fr',
                    'courses.name_kh',
                    'courses.time_course',
                    'courses.time_tp',
                    'courses.time_td',
                    'courses.credit',
                    'courses.department_id',
                    'courses.degree_id',
                    'courses.grade_id',
                    'courses.department_option_id',
                    'courses.semester_id',
                    'degrees.code as degree_code',
                    'departments.code as department_code',
                    'departmentOptions.code as option'
                ])
                ->orderBy('courses.degree_id', 'asc')
                ->orderBy('courses.grade_id', 'asc')
                ->orderBy('courses.semester_id', 'asc')
                ->get();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $department_id = $employee->department->id;

            $options = DepartmentOption::where('department_id', $employee->department_id)->get();
            $raw_courses = Course::where('courses.department_id', $department_id)
                ->orWhere('courses.responsible_department_id', $employee->department_id)
                ->join('degrees', 'degrees.id', '=', 'courses.degree_id')
                ->join('departments', 'departments.id', '=', 'courses.department_id')
                ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'courses.department_option_id')
                ->select([
                    'courses.id',
                    'courses.name_en',
                    'courses.name_fr',
                    'courses.name_kh',
                    'courses.time_course',
                    'courses.time_tp',
                    'courses.time_td',
                    'courses.credit',
                    'courses.department_id',
                    'courses.degree_id',
                    'courses.grade_id',
                    'courses.department_option_id',
                    'courses.semester_id',
                    'degrees.code as degree_code',
                    'departments.code as department_code',
                    'departmentOptions.code as option'
                ])
                ->orderBy('courses.degree_id', 'asc')
                ->orderBy('courses.grade_id', 'asc')
                ->orderBy('courses.semester_id', 'asc')
                ->get();
        }
        $courses = [];

        foreach ($raw_courses as $raw_course) {
            if (!isset($courses[$raw_course->department_code])) {
                $courses[$raw_course->department_code] = array();
            }
            array_push($courses[$raw_course->department_code], $raw_course);
        }

        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin', 'id')->toArray();
        $degrees = Degree::lists('name_kh', 'id')->toArray();
        $grades = Grade::lists('name_kh', 'id')->toArray();
        $semesters = Semester::lists("name_kh", "id");
        $competency_types = CompetencyType::lists('name', 'id')->toArray();

        return view('backend.course.courseAnnual.edit', compact('competency_types', 'courseAnnual', 'departments', 'academicYears', 'degrees', 'grades', 'courses', 'options', 'semesters', 'groups', 'midterm', 'final', 'other_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseAnnualRequest $request
     * @param  int $id ---course-annual-id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseAnnualRequest $request, $id)
    {
        $input = $request->all();
        $midterm_id = $request->midterm_percentage_id;
        $final_id = $request->final_percentage_id;
        $count_absence = $request->is_counted_absence;

        $midterm = [
            'name' => 'Midterm-' . $request->midterm_score . '%',
            'percent' => isset($request->midterm_score) ? (int)$request->midterm_score : 0,
            'percentage_type' => 'normal'
        ];

        $final = [
            'name' => 'Final-' . $request->final_score . '%',
            'percent' => (int)$request->final_score,
            'percentage_type' => 'normal'
        ];

        $updateCourseAannual = $this->courseAnnuals->update($id, $input);
        if ($updateCourseAannual) {

            if ($request->groups) {
                $create = $this->updateCourseAnnualClassByCourseAnnual($updateCourseAannual->id, $request->groups);
            } else {
                $create = true;
            }

            if ($create) {

                if (!isset($count_absence)) {
                    //---if the course doese not count the absence ..then find absence score and delete
                    $absences = DB::table('absences')->where('course_annual_id', $id);
                    if ($absences->get()) {
                        $absences->delete();
                    }
                }

                if (isset($midterm_id) || isset($final_id)) {
                    if (isset($midterm_id)) {//----record score midterm has been created
                        if ($midterm['percent'] > 0) {//---score midterm requested from user---
                            //----make change the record ---
                            $scores = DB::table('scores')->where('course_annual_id', $id);//---prevent if percentage created but score record was not created

                            if ($scores->get()) {

                                $check_percentage = DB::table('percentages')->where('id', $midterm_id)->first();
                                if ($check_percentage->percent != $midterm['percent']) {

                                    //---update percentage
                                    $this->percentages->update($midterm_id, $midterm);
                                    $this->percentages->update($final_id, $final);
                                    //--delete scores from table scores
                                    $scores->update(['score' => null]);
                                } else {

                                    if (isset($final_id)) {
                                        $check_final_percentage = DB::table('percentages')->where('id', $final_id)->first();

                                        if ($check_final_percentage->percent != $final['percent']) {
                                            //---update percentage
                                            $this->percentages->update($final_id, $final);
                                            $this->percentages->update($midterm_id, $midterm);
                                            //--delete scores from table scores
                                            $scores->update(['score' => null]);
                                        }

                                    }
                                }

                            } else {
                                $this->createScorePercentage($request->midterm_score, $request->final_score, $id);
                            }

                        } else {

                            //---$midterm_id is the percentage id for midterm score
                            //---the score midterm is requested to be 0----
                            //---so we have to delete the existing score

                            $old_midterm_percentage = DB::table('percentages')->where('id', $midterm_id)->get();
                            $old_midterm_percentage = $old_midterm_percentage[0];

                            if ($old_midterm_percentage->percent != 0) {
                                $scores = DB::table('scores')->where(function ($query) use ($midterm_id, $id) {
                                    $query->where('course_annual_id', '=', $id);
                                })->select('scores.id as score_id');
                                if ($scores->get() > 0) {
                                    $scores->delete();
                                }

                                //----delete  percentage----
                                $midterm_percentage = DB::table('percentages')->whereIn('id', [$midterm_id, $final_id]);
                                if ($midterm_percentage->get()) {
                                    $midterm_percentage->delete();
                                }
                                //---- create new score
                                $this->createScorePercentage($midterm['percent'], $final['percent'], $id);
                            }
                        }
                    } else {

                        //---score midterm has not been created...this case the course has only score final

                        if ($midterm['percent'] > 0) {//----if the request change score midterm bigger than 0 ::mean:: they want to create score midterm

                            //----delete previous score then recreate them ---
                            $scores = DB::table('scores')->where('scores.course_annual_id', $id);
                            if ($scores->get()) {
                                $scores->delete();
                            }
                            $delete_final_percentage = DB::table('percentages')->where('id', $final_id)->delete();
                            $this->createScorePercentage($request->midterm_score, $request->final_score, $id);

                        } else {

                            //----update only the final-percentage

                            $this->percentages->update($final_id, $final);
                        }
                    }

                } else {
                    $this->createScorePercentage($request->midterm_score, $request->final_score, $id);
                }
                return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
            }
        }

        return redirect()->back()->withFlashError('Not Updated');
    }

    /**
     * @param $courseAnnualId
     * @param array $groups
     * @return bool
     */

    private function updateCourseAnnualClassByCourseAnnual($courseAnnualId, array $groups = array())
    {
        $delete = DB::table('course_annual_classes')->where([
            ['course_annual_id', $courseAnnualId],
            ['course_session_id', null],
        ]);

        $data = [
            'groups' => $groups,
            'course_annual_id' => $courseAnnualId
        ];
        //---if the $delete hase no record the delete method will be error
        if (count($delete->get()) > 0) {

            $delete = $delete->delete();
            if ($delete) {
                $create = $this->courseAnnualClasses->create($data);
            } else {
                $create = false;
            }
        } else {
            $create = $this->courseAnnualClasses->create($data);
        }

        if ($create) {
            return true;
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update_score_per(Request $request, $id)
    {
        $this->courseAnnuals->update_score_per($id, $request->all());
//        return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));

        return Response::json(ResponseUtil::makeResponse("ok", "ok"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCourseAnnualRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCourseAnnualRequest $request, $id)
    {


        /*---delete competency score--*/

        $competencyScores = DB::table('competency_scores')
            ->where('course_annual_id', $id);

        if (count($competencyScores->get()) > 0) {

            $this->courseAnnualScores->getUserLog($competencyScores->get(), 'CompetencyScore', 'Delete');
            $competencyScores->delete();
        }

        /*---end delete competency---*/


        $scoreByCourseAnnualId = DB::table('scores')->where('course_annual_id', $id);
        $averages = Average::where('course_annual_id', $id);
        if ($totalScore = $averages->get()) {
            $averages->delete();
            $this->courseAnnualScores->getUserLog($totalScore, 'Average', 'Delete');
        }

        $absences = Absence::where('course_annual_id', $id);
        if ($scoreAbsence = $absences->get()) {
            $absences->delete();
            $this->courseAnnualScores->getUserLog($scoreAbsence, 'Absence', 'Delete');
        }

        if (count($scoreByCourseAnnualId->get()) > 0) {

            $percentageIds = DB::table('percentage_scores')
                ->whereIn('percentage_scores.score_id', $scoreByCourseAnnualId->lists('id'))
                ->distinct('percentage_id')->lists('percentage_id');
            $percentages = DB::table('percentages')->whereIn('id', $percentageIds);
            if ($percentages->get()) {
                $this->courseAnnualScores->getUserLog($percentages->get(), 'Percentage', 'Delete');
                $percentages->delete();
            }

            $this->courseAnnualScores->getUserLog($scoreByCourseAnnualId->get(), 'Score', 'Delete');
            $scoreByCourseAnnualId->delete();

            $this->courseAnnuals->destroy($id);
            return Response::json(['status' => true, 'message' => 'Deleted!']);

        } else {
            $this->courseAnnuals->destroy($id);
            return Response::json(['status' => true, 'message' => 'Deleted!']);
        }
        return Response::json(['status' => false, 'message' => 'Deleted Error!']);


        //return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data(Request $request)
    {

        $courseAnnuals = CourseAnnual::leftJoin('courses', 'course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees', 'course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('academicYears', 'course_annuals.academic_year_id', '=', 'academicYears.id')
            ->leftJoin('departments', 'course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'course_annuals.degree_id', '=', 'degrees.id')
            ->leftJoin('grades', 'course_annuals.grade_id', '=', 'grades.id')
            ->leftJoin('semesters', 'course_annuals.semester_id', '=', 'semesters.id')
            ->leftJoin('departmentOptions', 'course_annuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments as rd', 'course_annuals.responsible_department_id', '=', 'rd.id')
            ->with("courseAnnualClass")
            ->select([
                'courses.name_kh as course',
                'course_annuals.id',
                'course_annuals.time_course',
                'course_annuals.time_tp',
                'course_annuals.time_td',
                'course_annuals.name_en as name',
                'course_annuals.semester_id',
                'course_annuals.active',
                'course_annuals.academic_year_id',
                'course_annuals.is_allow_scoring',
                'employees.name_latin as employee_id',
                'academicYears.name_kh as academic_year',
                'semesters.name_kh as semester',
                'course_annuals.course_id',
                'rd.code as responsible_department_name',
                'course_annuals.responsible_department_id',
                'departmentOptions.code as department_option',
                DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
            ])
            ->orderBy("courses.degree_id", "ASC")
            ->orderBy("courses.department_id", "ASC")
            ->orderBy("courses.grade_id", "ASC")
            ->orderBy("course_annuals.semester_id", "ASC");

        $datatables = app('datatables')->of($courseAnnuals);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $allGroups = DB::table('groups')->select('id', 'code')->lists('code', 'id');

        $datatables
            ->addColumn('mark', function ($courseAnnual) {
                return "<img class='image_mark' src='" . url('img/arrow.png') . "' />";
            })
            ->editColumn('name', function ($courseAnnual) use ($allGroups) {
                ob_start();
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <span style="display: none" class="course_id"><?php echo $courseAnnual->id ?></span>
                        <h4>
                            <?php
                            echo $courseAnnual->name;
                            ?>
                        </h4>
                        <span>(C=<?php echo $courseAnnual->time_course ?> | TD=<?php echo $courseAnnual->time_td ?> | TP= <?php echo $courseAnnual->time_tp ?>
                            )</span>
                    </div>
                    <div class="col-md-4">
                        <?php
                        echo $courseAnnual->class;
                        if ($courseAnnual->department_option != "") {
                            echo $courseAnnual->department_option;
                        }
                        if ($courseAnnual->responsible_department_name != null) {
                            echo "<span style='color: darkred;'> (" . $courseAnnual->responsible_department_name . ")</span>";
                        }
                        ?>
                        <br/>
                        <?php
                        $a = "";

                        $courseClass = $courseAnnual->courseAnnualClass->toArray();

                        foreach ($courseClass as $obj_group) {
                            if ($obj_group['group_id']) {
                                $a = $a . " " . (isset($allGroups[$obj_group['group_id']]) ? $allGroups[$obj_group['group_id']] : '');
                            }
                        }
                        echo $a;
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $courseAnnual->semester . " | " . $courseAnnual->academic_year ?>
                    </div>
                </div>
                <?php
                $html = ob_get_clean();
                return $html;
            })
            ->setRowClass(function ($courseAnnual) {
                return ($courseAnnual->is_allow_scoring == "no" ? 'score_disabled' : '');
            })
            ->addColumn('action', function ($courseAnnual) use ($employee) {

                if ($courseAnnual->is_allow_scoring == "no") {
                    $action_toggle_scoring = ' <a href="' . route('admin.course.course_annual.toggle_scoring', $courseAnnual->id) . '?operation=yes" class="btn btn-xs btn-warning toggle_scoring"><i class="fa fa-toggle-on" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enable Scoring"></i></a>';
                    $action_input_score = ' <a href="' . route('admin.course.form_input_score_course_annual', $courseAnnual->id) . '" class="btn btn-xs btn-default input_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . 'input score' . '"></i></a>';
                } else if ($courseAnnual->is_allow_scoring == "yes") {
                    $action_toggle_scoring = ' <a href="' . route('admin.course.course_annual.toggle_scoring', $courseAnnual->id) . '?operation=no" class="btn btn-xs btn-success toggle_scoring"><i class="fa fa-toggle-off" data-toggle="tooltip" data-placement="top" title="" data-original-title="Disable Scoring"></i></a>';
                    $action_input_score = ' <a href="' . route('admin.course.form_input_score_course_annual', $courseAnnual->id) . '" class="btn btn-xs btn-info input_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . 'input score' . '"></i></a>';
                } else {
                    $action_toggle_scoring = ' <a href="' . route('admin.course.course_annual.toggle_scoring', $courseAnnual->id) . '?operation=no" class="btn btn-xs btn-default toggle_scoring"><i class="fa fa-toggle-off" data-toggle="tooltip" data-placement="top" title="" data-original-title="Disable Scoring"></i></a>';
                    $action_input_score = ' <a href="' . route('admin.course.form_input_score_course_annual', $courseAnnual->id) . '" class="btn btn-xs btn-info input_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . 'input score' . '"></i></a>';
                }

                $action_view_score = ' <a href="' . route('admin.course.form_input_score_course_annual', $courseAnnual->id) . '?mode=view' . '" class="btn btn-xs btn-default view_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . 'view score' . '"></i></a>';

                $action_edit_score = ' <a href="' . route('admin.course.course_annual.edit', $courseAnnual->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.crud.edit') . '"></i> </a>';
                $action_delete_score = ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.course.course_annual.destroy', $courseAnnual->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';

                if (access()->hasRole("Administrator")) { // This is admin
                    return $action_toggle_scoring .
                        $action_input_score .
                        $action_edit_score .
                        $action_delete_score;
                } else {

                    $actions = "";

                    // Check if this is his/her course and he/she has permission to input score
                    if (Auth::user()->allow('disable-enable-input-score-into-course-annual')) {
                        $actions = $actions . $action_toggle_scoring;
                    }

                    if (Auth::user()->allow('input-score-course-annual')) {
                        $my_courses = CourseAnnual::where('employee_id', $employee->id)->lists('id')->toArray();
                        if (in_array($courseAnnual->id, $my_courses)) {
                            $actions = $actions . $action_input_score;
                        } else if (Auth::user()->allow('view-all-score-course-annual')) {
                            $actions = $actions . $action_view_score;
                        }
                    } else if (Auth::user()->allow('view-all-score-course-annual')) {
                        $actions = $actions . $action_view_score;
                    }

                    if (Auth::user()->allow('edit-courseAnnuals')) {
                        $actions = $actions . $action_edit_score;
                    }

                    if (Auth::user()->allow('delete-courseAnnuals')) {
                        $actions = $actions . $action_delete_score;
                    }

                    return $actions;

                }

            });
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('course_annuals.academic_year_id', '=', $academic_year);
        } else {
            $last_academic_year_id = AcademicYear::orderBy('id', 'desc')->first()->id;
            $datatables->where('course_annuals.academic_year_id', '=', $last_academic_year_id);
        }
        if ($degree = $datatables->request->get('degree')) {
            $datatables->where('course_annuals.degree_id', '=', $degree);
        }
        if ($grade = $datatables->request->get('grade')) {
            $datatables->where('course_annuals.grade_id', '=', $grade);
        }

        if ($semester = $datatables->request->get('semester')) {
            $datatables->where('course_annuals.semester_id', '=', $semester);
        }

        if ($deptOption = $datatables->request->get('dept_option')) {
            $datatables->where('course_annuals.department_option_id', '=', $deptOption);
        }
        if ($group = $datatables->request->get('student_group')) {
            $datatables->where('course_annual_classes.group', '=', $group);
        }

        if ($department = $datatables->request->get('department')) {
            if (auth()->user()->allow("view-all-score-in-all-department")) {
                // user has permission to view all course/score in all department
                // This equal to admin, so no need to check anything more. Just return whatever they request
                $datatables->where('course_annuals.department_id', '=', $department);
            } else {
                // The requested department is same as user's department
                // So return every courses in that department
                $datatables->where('course_annuals.department_id', $department);

                if ($department != $employee->department->id) {
                    // The requested department in not the same as user's department
                    // So return only courses that user responsbile in given department
                    $datatables->where('course_annuals.responsible_department_id', $employee->department->id);
                }
            }
        }

        if (auth()->user()->allow("view-all-score-course-annual")) {   // This one is might be chef department, he can view all course/score for all teacher
            if ($lecturer = $datatables->request->get('lecturer')) {
                $datatables->where('course_annuals.employee_id', '=', $lecturer);
            }
        } else {
            $datatables = $datatables->where('course_annuals.employee_id', $employee->id);
        }

        $datatables = $datatables->get();

        return $datatables->make(true);
    }

    public function request_import()
    {
        return view('backend.course.courseAnnual.import');
    }

    public function import(ImportCourseAnnualRequest $request)
    {
        $now = Carbon::now()->format('Y_m_d_H');
        // try to move uploaded file to a temporary location
        if ($request->file('import') != null) {
            $import = $now . '.' . $request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/temp/' . $import;
            DB::beginTransaction();
            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {
                    $results->each(function ($row) {
                        // Clone an object for running query in studentAnnual
                        $courseAnnual_data = $row->toArray();
                        $courseAnnual_data["created_at"] = Carbon::now();
                        $courseAnnual_data["create_uid"] = auth()->id();
                        $courseAnnual = CourseAnnual::create($courseAnnual_data);
                        $this->courseAnnualScores->getUserLog($courseAnnual_data, 'CourseAnnual', 'Import');
                        $first = false;
                    });
                });

            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
            return redirect(route('admin.backend.course.course_annual.index'));
        }
    }

    public function getAllDepartments(CourseAnnualAssignmentRequest $request)
    {

        $allDepartments = $this->get_department_tree($request);
        return Response::json($allDepartments);
    }

    public function getAllTeacherByDepartmentId(CourseAnnualAssignmentRequest $request)
    {

        $department_id = explode('_', $_GET['id'])[1];
        $teachers = $this->all_teacher_by_department($request, $department_id);
        return Response::json($teachers);

    }

    public function getSeletedCourseByTeacherID(CourseAnnualAssignmentRequest $request)
    {

        $parent_id = $_GET['id'];
        $teacher_id = explode('_', $_GET['id'])[3];
        $courses = $this->selected_course_by_teacher($request, $parent_id, $teacher_id);
        return Response::json($courses);
    }

    public function getAllCourseByDepartment(Request $request)
    {

        $deptId = explode('_', $_GET['id'])[1];
        $arrayCourses = $this->allCourseByDepartment($request, $deptId);
        return Response::json($arrayCourses);

    }

    public function studentGroupByDept(Request $request)
    {

        $nodeId = explode('_', $_GET['id']);
        $arrayGroup = $this->group_student_by_department($request, $nodeId);
        return Response::json($arrayGroup);
    }

    public function courseAssignment(CourseAnnualAssignmentRequest $request)
    {


        $academicYear = AcademicYear::where('id', $request->academic_year_id)->first();
        $departmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $deptOption = $request->department_option_id;
        $semesterId = $request->semester_id;
        $departmentOptions = $this->deptHasOption($departmentId);

        if (auth()->user()->allow("view-all-score-in-all-department")) {
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $user_department_id = null;

        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $user_department_id = $employee->department->id;


        }
        $academicYears = AcademicYear::lists('name_latin', 'id')->toArray();
        $degrees = Degree::lists('name_en', 'id')->toArray();
        $grades = Grade::orderBy('id')->lists('name_en', 'id')->toArray();
        $semesters = Semester::orderBy('id')->lists("name_en", "id");

        if ($deptOption == '') {
            $deptOption = null;
        }

        return view('backend.course.courseAnnual.includes.popup_course_assignment', compact(
            'academicYear', 'departmentId', 'gradeId', 'academicYears', 'degrees', 'grades', 'semesters', 'departmentOptions', 'departments', 'user_department_id',
            'degreeId', 'deptOption', 'semesterId'));
    }

    public function removeCourse(CourseAnnualAssignmentRequest $request)
    {

        $input = $request->course_selected;
        $status_remove = $this->remove_course($input);
        if ($status_remove) {
            return Response::json(['status' => true, 'message' => 'You Have Removed Selected Courses']);
        }
    }


    public function assignCourse(CourseAnnualAssignmentRequest $request)
    {

        return Response::json($this->assign_course($request));/*---courseSessionTrait---*/
    }

    /*--form edit course-session in course assignment panel---*/
    public function formEditCourseAnnual(CourseAnnualAssignmentRequest $request)
    {

        return $this->edit_course_session($request);

    }

    public function updateCourseSession($courseSessionId, CourseAnnualAssignmentRequest $request)
    { /*---update course session in course assignment pane --*/

        return $this->update_course_session($courseSessionId, $request); /*--course session trait---*/
    }

    public function douplicateCourseAnnual(CourseAnnualAssignmentRequest $request)
    { /*--douplicate course session not course annual --*/

        return Response::json($this->duplicate_couse_session($request));
    }


    //---here delete course session
    public function deleteCourseSession(CourseAnnualAssignmentRequest $request)
    {

        return Response::json($this->delete_course_session($request));
    }


    public function generateCourseAnnual(Request $request)
    {

        return Response::json($this->generate_course_annual($request)); /*--course annual trait--*/
    }

    public function getFormScoreByCourse(Request $request, $courseAnnualId)
    {

        $properties = $this->dataSendToView($courseAnnualId);
        if (($properties['course_annual']->competency_type_id != null) and ($properties['course_annual']->normal_scoring == false)) {
            return redirect("/admin/course/course/get-form-proficency?course_annual_id=" . $courseAnnualId);
        }
        $courseAnnual = $properties['course_annual'];
        $availableCourses = $properties['available_course'];
        $mode = null;
        $allowCloningScore = false;

        if (auth()->user()->allow("view-all-score-in-all-department")) {

            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $department_id = null;

        } else {

            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $department_id = $employee->department->id;

        }

        $academicYears = AcademicYear::orderBy("id", "desc")->lists('name_latin', 'id')->toArray();
        $degrees = Degree::lists('name_en', 'id')->toArray();
        $grades = Grade::orderBy('id')->lists('name_en', 'id')->toArray();

        $semesters = Semester::orderBy('id')->lists('name_en', 'id')->toArray();
        $departmentOptions = DB::table('departmentOptions')->get();


        if (access()->hasRole("Administrator")) {
            $mode = "edit";

        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $my_courses = CourseAnnual::where('employee_id', $employee->id)->lists('id')->toArray();

            if (Auth::user()->allow('input-score-course-annual') && in_array($courseAnnualId, $my_courses)) {
                $mode = "edit";
            } else if (Auth::user()->allow('view-all-score-course-annual')) {
                $mode = "view";
            } else {
                // This course is not belong to current user, and user don't have permission to view score
                return view('backend.course.courseAnnual.includes.no_permission_to_score');
            }
        }

        if ($courseAnnual->responsible_department_id) {
            $resDepartment = Department::where('id', $courseAnnual->responsible_department_id)->first();
            if ($resDepartment->is_vocational) {
                $allowCloningScore = true;
            }
        }

        return view('backend.course.courseAnnual.includes.form_input_score_course_annual',
            compact(
                'courseAnnualId', 'courseAnnual', 'availableCourses', 'mode', 'allowCloningScore',
                'departments', 'academicYears', 'degrees', 'grades', 'semesters', 'departmentOptions', 'department_id'
            ));

    }

    public function getCourseAnnualScoreByAjax(Request $request)
    {

        return $this->handsonTableData($request->course_annual_id, $request_group = null);
    }

    private function handsonTableData($courseAnnualId, $request_group)
    {

        $arrayData = [];
        $courseAnnual = CourseAnnual::where('id', $courseAnnualId)->first();
        $department = Department::where('id', $courseAnnual->department_id)->first(); // owner of the department course
        $propArrayIds = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnual);

        $department_ids = $propArrayIds['department_id'];
        $degree_ids = $propArrayIds['degree_id'];
        $grade_ids = $propArrayIds['grade_id'];
        $department_option_ids = $propArrayIds['department_option_id'];
        $groups = $propArrayIds['group'];// lists of group_ids
        $scoreProps = $this->scoreAnnualProp($courseAnnualId);

        $averageByCourseAnnual = $this->averageByCourseAnnual($courseAnnualId);

        $columnName = collect($scoreProps)->keyBy('percentage_id')->toArray();
        $headers = $this->handsonTableHeaders($columnName, $courseAnnual);

        $columnHeader = $headers['colHeader'];
        $columns = $headers['column'];

        if ($courseAnnual->is_allow_scoring == "no") {
            /*---set readonly true foreach column of the score ---*/
            $columns = collect($columns)->map(function ($item, $key) {
                $item['readOnly'] = true;
                return $item;
            })->toArray();
        } else if ($courseAnnual->is_allow_scoring == "only_retake") {
            /*--- only retake score is allowed to input ---*/
            $columns = collect($columns)->map(function ($item, $key) {
                if ($item['data'] != "resit") {
                    $item['readOnly'] = true;
                }
                return $item;
            })->toArray();
        } else {

            /*---check if the selected course is assiged for the responsible department ----*/
            /*---we set readonly for each cell inputed score -----*/
            if ($courseAnnual->responsible_department_id) {
                $resDepartment = Department::where('id', $courseAnnual->responsible_department_id)->first();
                if ($resDepartment->is_vocational) {

                    $columns = collect($columns)->map(function ($item, $key) {
                        $item['readOnly'] = true;
                        return $item;
                    })->toArray();

                }
            }
        }

        $colWidths = $headers['colWidth'];
        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId($department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id);
        $allScoreByCourseAnnual = $this->studentAnnualScores($scoreProps, $courseAnnualId);

        if ($allScoreByCourseAnnual == false) {
            return json_encode([
                'status' => false,
                'colWidths' => [],
                'data' => [],
                'columnHeader' => [],
                'columns' => [],
                'message' => 'Your course has not been assign score. Please Updage!',
                'should_add_score' => false
            ]);
        }
        $allNumberAbsences = $this->getAbsenceFromDB($courseAnnualId);

        if ($courseAnnual->is_having_resitted) {
            $resitScores = $this->resitScoreFromDB($courseAnnualId);//DistributionDepartmentTrait/ScoreProp
        }
        if (count($department_option_ids) > 0) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }

        //----if has reqest selection groups in one course annual ----
        if ($request_group != null) {

            $studentAnnualIds = DB::table('group_student_annuals')->whereIn('group_id', (is_array($request_group) ? $request_group : [$request_group]))
                ->where('semester_id', $courseAnnual->semester_id);

            if ($department->is_vocational) {
                $studentAnnualIds = $studentAnnualIds->where('group_student_annuals.department_id', $department->id)->lists('student_annual_id');
            } else {
                $studentAnnualIds = $studentAnnualIds->whereNull('group_student_annuals.department_id')->lists('student_annual_id');
            }


            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)
                ->orderBy('students.name_latin');

            if ($courseAnnual->semester_id > 1) {

                $studentByCourse = $studentByCourse
                    ->where(function ($query) {
                        $query->where('students.radie', '=', false)
                            ->orWhereNull('students.radie');
                    })
                    ->orderBy('students.name_latin')->get();

            } else {
                $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)->orderBy('students.name_latin')->get();
            }

        } else {

            if (count($groups) > 0) {

                $studentAnnualIds = DB::table('group_student_annuals')->whereIn('group_id', $groups)
                    ->where('semester_id', $courseAnnual->semester_id);

                if ($department->is_vocational) {

                    $studentAnnualIds = $studentAnnualIds->where('group_student_annuals.department_id', $department->id)->lists('student_annual_id');
                } else {
                    $studentAnnualIds = $studentAnnualIds->whereNull('group_student_annuals.department_id')->lists('student_annual_id');
                }

                $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)
                    ->orderBy('students.name_latin');

                if ($courseAnnual->semester_id > 1) {

                    $studentByCourse = $studentByCourse
                        ->where(function ($query) {
                            $query->where('students.radie', '=', false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin')->get();

                } else {
                    $studentByCourse = $studentByCourse->orderBy('students.name_latin')->get();
                }

            } else {

                if ($courseAnnual->semester_id > 1) {

                    $studentByCourse = $studentByCourse
                        ->where(function ($query) {
                            $query->where('students.radie', '=', false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin')->get();

                } else {
                    $studentByCourse = $studentByCourse->orderBy('students.name_latin')->get();
                }
            }
        }
        $listStudentIds = collect($studentByCourse)->pluck('student_id')->toArray();

        $redoubleStudentObjects = $this->redoubleByStudentIds($listStudentIds, $courseAnnual->academic_year_id);

        // ---- sort student by name ---
        usort($studentByCourse, function ($a, $b) {
            return strcmp(strtolower($a->name_latin), strtolower($b->name_latin));
        });

        //----------------find student score if they have inserted


        if ($studentByCourse) {

            foreach ($studentByCourse as $student) {
                $scoreData = [];

                if (!isset($redoubleStudentObjects[$student->student_id])) {

                    $totalScore = 0;
                    $checkPercent = 0;
                    $scoreIds = []; // there are many score type for one subject and one student :example TP, Midterm, Final-exam
                    $checkFraudAbsScore = 0;// to find if student has both absence and fraud in each score

                    $studentScore = isset($allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id]) ? $allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id] : [];

                    if ($courseAnnual->is_counted_absence) {

                        $scoreAbsence = isset($allNumberAbsences[$courseAnnual->id][$student->student_annual_id]) ? $allNumberAbsences[$courseAnnual->id][$student->student_annual_id] : null;// get number of absence from database
                        //--calculate score absence to sum with the real score
                        $totalCourseHours = ($courseAnnual->time_course + $courseAnnual->time_tp + $courseAnnual->time_td);
                        $scoreAbsenceByCourse = $this->floatFormat(((($totalCourseHours) - (isset($scoreAbsence) ? $scoreAbsence->num_absence : 0)) * ScoreEnum::Score_10) / ((($totalCourseHours != 0) ? $totalCourseHours : 1)));
                        $totalScore = $totalScore + (($scoreAbsenceByCourse >= 0) ? $scoreAbsenceByCourse : 0);
                    }

                    if ($studentScore) {

                        foreach ($studentScore as $score) {

                            $checkPercent = $checkPercent + $score->percent; // we check the percentage if it is equal or bigger than 90 then we should now allow teacher to create more score

                            if ((strtoupper($score->score) == ScoreEnum::Fraud) || ($score->score == ScoreEnum::Absence)) {
                                $checkFraudAbsScore++;// to count each score of one student who has been frauded in exam or absence
                                $totalScore = $totalScore;
                            } else if ($score->score == "" || $score->score == null) {
                                $totalScore = $totalScore + 0;// calculate score for student annual
                            } else {
                                $totalScore = $totalScore + $score->score;// calculate score for stuent annual
                            }

                            $scoreData[$score->name] = (($score->score != null) ? $score->score : null);//assign each score value midterm/ final
                            $scoreData['percentage_id' . '_' . $score->name] = $score->percentage_id;
                            $scoreData['score_id' . '_' . $score->name] = $score->score_id;
                            $scoreIds[] = $score->score_id;
                        }
                    } else {
                        $scoreData = [];
                    }

                    /*------store average(a total score of each courseannual in table averages)-----------*/

                    if (count($studentScore) == $checkFraudAbsScore) {
                        $input = [
                            'course_annual_id' => $courseAnnualId,
                            'student_annual_id' => $student->student_annual_id,
                            'average' => ScoreEnum::Zero
                        ];
                    } else {

                        $input = [
                            'course_annual_id' => $courseAnnualId,
                            'student_annual_id' => $student->student_annual_id,
                            'average' => $this->floatFormat($totalScore)
                        ];

                    }

                    if (count($averageByCourseAnnual) > 0) {

                        $allStudentScores = $averageByCourseAnnual[$courseAnnualId];

                        if (isset($allStudentScores[$student->student_annual_id])) {
                            $storeTotalScore = $this->createOrUpdateTotalScoreCourseAnnual($input, $allStudentScores[$student->student_annual_id], $courseAnnual);
                            /*if(!$storeTotalScore) {
                                return Response::json(['status' => false, 'message' => 'You Dont have permission to view the score. Please ask administrator to allow the permission!']);
                            }*/
                        } else {
                            $storeTotalScore = $this->createOrUpdateTotalScoreCourseAnnual($input, [], $courseAnnual);
                            /*if(!$storeTotalScore) {
                                return Response::json(['status' => false, 'message' => 'You Dont have permission to view the score. Please ask administrator to allow the permission!']);
                            }*/
                        }
                    } else {
                        $storeTotalScore = $this->createOrUpdateTotalScoreCourseAnnual($input, [], $courseAnnual);

                        /*if(!$storeTotalScore) {
                            return Response::json(['status' => false, 'message' => 'You Dont have permission to view the score. Please ask administrator to allow the permission!']);
                        }*/
                    }

                    /*------------end of insert of update total score -------------*/

                    // ------create element data array for handsontable

                    if ($courseAnnual->is_counted_absence) {

                        if ($courseAnnual->is_having_resitted) {

                            $element = array(
                                'student_annual_id' => $student->student_annual_id,
                                'student_id_card' => $student->id_card,
                                'student_name' => strtoupper($student->name_latin),
                                'student_gender' => $student->code,
                                'absence' => (string)(($scoreAbsenceByCourse >= 0) ? $scoreAbsenceByCourse : 10),
                                'num_absence' => isset($scoreAbsence) ? $scoreAbsence->num_absence : null,
                                'resit' => isset($resitScores[$student->student_annual_id]) ? $resitScores[$student->student_annual_id]->resit_score : null,
                                'average' => $this->floatFormat($totalScore),
                                'notation' => isset($scoreAbsence) ? $scoreAbsence->notation : null//$storeTotalScore->description
                            );
                        } else {
                            $element = array(
                                'student_annual_id' => $student->student_annual_id,
                                'student_id_card' => $student->id_card,
                                'student_name' => strtoupper($student->name_latin),
                                'student_gender' => $student->code,
                                'absence' => (string)(($scoreAbsenceByCourse >= 0) ? $scoreAbsenceByCourse : 10),
                                'num_absence' => isset($scoreAbsence) ? $scoreAbsence->num_absence : null,
                                'average' => $this->floatFormat($totalScore),
                                'notation' => isset($scoreAbsence) ? $scoreAbsence->notation : null//$storeTotalScore->description
                            );
                        }


                    } else {

                        if ($courseAnnual->is_having_resitted) {
                            $element = array(
                                'student_annual_id' => $student->student_annual_id,
                                'student_id_card' => $student->id_card,
                                'student_name' => strtoupper($student->name_latin),
                                'student_gender' => $student->code,
                                'resit' => isset($resitScores[$student->student_annual_id]) ? $resitScores[$student->student_annual_id]->resit_score : null,
                                'average' => $totalScore,
                                'notation' => isset($scoreAbsence) ? $scoreAbsence->notation : null//$storeTotalScore->description
                            );
                        } else {
                            $element = array(
                                'student_annual_id' => $student->student_annual_id,
                                'student_id_card' => $student->id_card,
                                'student_name' => strtoupper($student->name_latin),
                                'student_gender' => $student->code,
                                'average' => $totalScore,
                                'notation' => isset($scoreAbsence) ? $scoreAbsence->notation : null//$storeTotalScore->description
                            );
                        }


                    }

                    $mergerData = array_merge($element, $scoreData);
                    $arrayData[] = $mergerData;

                }

            }
            return json_encode([
                'status' => true,
                'colWidths' => $colWidths,
                'data' => $arrayData,
                'columnHeader' => $columnHeader,
                'columns' => $columns,
                'should_add_score' => true
            ]);
        } else {

            return Response::json(['status' => false, 'message' => 'No Student Recod', 'course_properties' => $courseAnnual]);
        }

    }

    public function handsonTableHeaders($columnName, $courseAnnual)
    {

        if ($courseAnnual->is_counted_absence) {

            $columnHeader = array(/*'Student_annual_id',*/
                'Student ID', 'Student Name', 'M/F', 'Abs', 'Abs-10%');
            $columns = array(
//            ['data' => 'student_annual_id', 'readOnly'=>true],
                ['data' => 'student_id_card', 'readOnly' => true],
                ['data' => 'student_name', 'readOnly' => true],
                ['data' => 'student_gender', 'readOnly' => true],
                ['data' => 'num_absence', 'type' => 'numeric'],
                ['data' => 'absence', 'readOnly' => true],
            );
            $colWidths = [80, 180, 55, 55];// width of each column


            if ($columnName) {

                foreach ($columnName as $column) {
                    $columnHeader = array_merge($columnHeader, array($column->name));
                    $columns = array_merge($columns, array(['data' => $column->name]));
                    $colWidths[] = 70;
                }

                if ($courseAnnual->is_having_resitted) {
                    $columns = array_merge($columns, array(['data' => 'resit'], ['data' => 'average', 'readOnly' => true, 'type' => 'numeric'], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Resit-Score', 'Total', 'Notation'));
                    $colWidths[] = 55;
                    $colWidths[] = 70;
                } else {
                    $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true, 'type' => 'numeric'], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Total', 'Notation'));
                    $colWidths[] = 70;
                }

            } else {


                if ($courseAnnual->is_having_resitted) {
                    $columns = array_merge($columns, array(['data' => 'resit'], ['data' => 'average', 'readOnly' => true], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Resit-Score', 'Average', 'Notation'));
                    $colWidths[] = 55;
                    $colWidths[] = 70;
                } else {
                    $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Average', 'Notation'));
                    $colWidths[] = 70;
                }

            }

        } else {

            $columnHeader = array(/*'Student_annual_id',*/
                'Student ID', 'Student Name', 'M/F');
            $columns = array(
//            ['data' => 'student_annual_id', 'readOnly'=>true],
                ['data' => 'student_id_card', 'readOnly' => true],
                ['data' => 'student_name', 'readOnly' => true],
                ['data' => 'student_gender', 'readOnly' => true]
            );
            $colWidths = [80, 180, 55];

            if ($columnName) {

                foreach ($columnName as $column) {
                    $columnHeader = array_merge($columnHeader, array($column->name));
                    $columns = array_merge($columns, array(['data' => $column->name]));
                    $colWidths[] = 70;
                }


                if ($courseAnnual->is_having_resitted) {
                    $columns = array_merge($columns, array(['data' => 'resit'], ['data' => 'average', 'readOnly' => true, 'type' => 'numeric'], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Resit-Score', 'Total', 'Notation'));
                    $colWidths[] = 55;
                    $colWidths[] = 70;
                } else {
                    $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true, 'type' => 'numeric'], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Total', 'Notation'));
                    $colWidths[] = 70;
                }


            } else {

                if ($courseAnnual->is_having_resitted) {
                    $columns = array_merge($columns, array(['data' => 'resit'], ['data' => 'average', 'readOnly' => true], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Resit-Score', 'Average', 'Notation'));
                    $colWidths[] = 55;
                    $colWidths[] = 70;
                } else {
                    $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true], ['data' => 'notation']));
                    $columnHeader = array_merge($columnHeader, array('Average', 'Notation'));
                    $colWidths[] = 70;
                }
            }
        }
        return [
            'colHeader' => $columnHeader,
            'column' => $columns,
            'colWidth' => $colWidths
        ];

    }

    private function studentAnnualScores($scores, $courseAnnualId)
    {
        $collect = collect($scores)->groupBy('course_annual_id')->toArray();
        if (isset($collect[$courseAnnualId])) {
            $secondCollect = collect($collect[$courseAnnualId])->groupBy('student_annual_id')->toArray();
            $arrayData[$courseAnnualId] = $secondCollect;
            return $arrayData;
        } else {
            return false;
        }

    }

    public function getAbsenceFromDB($courseAnnualId)
    {
        $arrayData = [];

        if (is_array($courseAnnualId)) {

            /*--this case we use only for cloning absence to student of Department SA or SF--*/

            $absences = DB::table('absences')->whereIn('course_annual_id', $courseAnnualId)->get();

            if ($absences) {
                $arrayData = collect($absences)->keyBy('student_annual_id')->toArray();
            }
        } else {

            $absences = DB::table('absences')->whereIn('course_annual_id', [$courseAnnualId])->get();
            if ($absences) {
                $collection = collect($absences)->groupBy('course_annual_id')->toArray();
                $arrayData[$courseAnnualId] = collect($collection[$courseAnnualId])->keyBy('student_annual_id')->toArray();
            }

        }

        return $arrayData;
    }

    public function createOrUpdateTotalScoreCourseAnnual($input, $averageByCourse, $courseAnnual)
    {


        if ($courseAnnual->is_allow_scoring != "no" || auth()->user()->allow("input-score-without-blocking")) {

            if ($averageByCourse) {
                /*---update total score of each course annual in table average ---*/

                $UpdateAverage = $this->averages->update($averageByCourse->id, $input);
                if ($UpdateAverage) {
                    return $UpdateAverage;
                }

            } else {
                /*---create new record for total score of each course annual in table average ---*/

                $storeAverage = $this->averages->create($input); // store total score then return collection-with ID
                if ($storeAverage) {
                    return $storeAverage;
                }
            }

        } else {

            return false;

            //throw new GeneralException("Permission denied.");
        }
    }

    public function saveScoreByCourseAnnual(Request $request)
    {

        $inputs = $request->data;

        $checkUpdate = 0;
        $checkNotUpdated = 0;
        if ($inputs) {

            $scores = DB::table('scores')->where('course_annual_id', $inputs[0]['course_annual_id'])->lists('scores.id');
            $courseAnnual = CourseAnnual::where('id', $inputs[0]['course_annual_id'])->first();
            $percentages = DB::table('percentages')
                ->join('percentage_scores', function ($query) use ($scores) {
                    $query->on('percentage_scores.percentage_id', '=', 'percentages.id')
                        ->whereIn('percentage_scores.score_id', $scores);
                })->select('percentages.*')->groupBy('percentages.id')->get();

            foreach ($inputs as $input) {

                if (isset($input['score_id'])) {

                    if ($input['score_id'] != null) {
                        $updateScore = $this->courseAnnualScores->update($input['score_id'], $input);

                        if ($updateScore) {
                            $checkUpdate++;
                        }

                    } else {
                        $checkNotUpdated++;
                    }
                } else {

                    /*---create new record score---*/
                    /*---check if student have score ----*/

                    $studentScore = DB::table('scores')->where([
                        ['course_annual_id', $input['course_annual_id']],
                        ['student_annual_id', $input['student_annual_id']]
                    ])->get();


                    if (count($studentScore) <= 2) {
                        $explode = explode('-', $request->percentage);
                        $percent = trim($explode[1], '%');

                        foreach ($percentages as $percentage) {
                            if ($percentage->percent == (int)$percent) {
                                $percentId = $percentage->id;
                            }
                        }

                        $inputScore = [

                            'course_annual_id' => $input['course_annual_id'],
                            'student_annual_id' => $input['student_annual_id'],
                            'academic_year_id' => $courseAnnual->academic_year_id,
                            'semester_id' => $courseAnnual->semester_id,
                            'grade_id' => $courseAnnual->grade_id,
                            'degree_id' => $courseAnnual->degree_id,
                            'department_id' => $courseAnnual->department_id,
                            'score' => $input['score'],
                            'percentage_id' => $percentId

                        ];

                        $createScore = $this->courseAnnualScores->create($inputScore);

                        if ($createScore) {
                            $checkUpdate++;
                        } else {
                            $checkNotUpdated++;
                        }
                    }


                }

            }
        }


        if ($checkUpdate == count($inputs) - $checkNotUpdated) {

            $reDrawTable = $this->handsonTableData($inputs[0]['course_annual_id'], $request_group = null);
            $reDrawTable = json_decode($reDrawTable, true);

            return Response::json(['handsontableData' => $reDrawTable, 'status' => true, 'message' => 'Score Saved!!']);
        } else {

            return Response::json(['handsontableData' => [], 'status' => false, 'message' => 'Score NOt Saved!!']);
        }
    }

    public function insertPercentageNameNPercentage(Request $request)
    {

        //this is to add new column name of the exam score ...and we have to initial the value 0 to the student for this type of exam

        $midterm = $request->percentage;
        $final = ScoreEnum::Midterm_Final - $midterm;
        $createScore = $this->createScorePercentage($midterm, $final, $request->course_annual_id);
        if ($createScore) {
            $reDrawTable = $this->handsonTableData($request->course_annual_id, $group = null);
            return $reDrawTable;
        }

    }

    public function storeNumberAbsence(Request $request)
    {

        $baseData = $request->baseData;
        $checkStore = 0;
        $checkUpdate = 0;
        $checkNOTUpdatOrStore = 0;
        if (count($baseData) > 0) {

            $status = 0;
            foreach ($baseData as $data) {
                if (is_numeric($data['num_absence']) || $data['num_absence'] == null) {
                    $status++;
                }
            }

            if ($status == count($baseData)) {

                $courseAnnualId = $baseData[0]['course_annual_id'];
                $absenceByCourse = $this->getStudentAbsence($courseAnnualId);

                foreach ($baseData as $data) {

                    if ($data['student_annual_id'] != null) {

                        if (count($absenceByCourse) > 0) {
                            $absences = $absenceByCourse;

                            if (isset($absences[$data['student_annual_id']])) {
                                //update absence
                                $studentAbsence = $absences[$data['student_annual_id']];
                                $update = $this->absences->update($studentAbsence->id, $data);
                                if ($update) {
                                    $checkUpdate++;
                                }
                            } else {

                                // store absence
                                $store = $this->absences->create($data);
                                if ($store) {
                                    $checkStore++;
                                }
                            }

                        } else {
                            // store absence
                            $store = $this->absences->create($data);
                            if ($store) {
                                $checkStore++;
                            }
                        }

                    } else {
                        $checkNOTUpdatOrStore++;
                    }
                }
            } else {
                $reDrawTable = $this->handsonTableData($data['course_annual_id'], $group = null);
                $reDrawTable = json_decode($reDrawTable);
                return Response::json(['status' => false, 'message' => 'There are null or String Value in cell!', 'handsonData' => $reDrawTable]);
            }

        }
        if ($checkStore + $checkUpdate == count($baseData) - $checkNOTUpdatOrStore) {
            $reDrawTable = $this->handsonTableData($data['course_annual_id'], $group = null);
            $reDrawTable = json_decode($reDrawTable);
            return Response::json(['status' => true, 'message' => 'Stored!', 'handsonData' => $reDrawTable]);
        }
    }

    private function getStudentAbsence($courseAnnualId)
    {

        $absences = collect(DB::table('absences')
            ->where('course_annual_id', $courseAnnualId)
            ->get())->keyBy('student_annual_id')->toArray();

        return $absences;
    }

//    --------------all course annual score  ---------------

    public function deleteScoreFromScorePercentage(Request $request)
    {

        $status = 0;

        $scores = Score::join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->where('course_annual_id', $request->course_annual_id)
            ->select('scores.id as score_id', 'percentages.id as percentage_id')
            ->get();


        $arrayPercentageId = [];
        foreach ($scores as $score) {
            $arrayPercentageId[$score->percentage_id] = $score->percentage_id;
        }

        foreach ($arrayPercentageId as $id) {
            $deletePercentage = $this->percentages->destroy($id);
            if ($deletePercentage) {
                $status++;
            }
        }

        $deleteScore = DB::table('scores')->where('course_annual_id', $request->course_annual_id)->delete();

        if ($deleteScore) {
            $reDrawTable = $this->handsonTableData($request->course_annual_id, $group = null);
            return $reDrawTable;
        }
    }

    public function formAllScoreSelection()
    {

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $academicYears = DB::table('academicYears')->orderBy('created_at', 'DSCE')->lists('name_latin', 'id');
        $departmentOptions = DB::table('departmentOptions')->orderBy('code')->get();
        $semesters = DB::table('semesters')->orderBy('created_at', 'ASC')->lists('name_en', 'id');
        $degrees = DB::table('degrees')->orderBy('created_at', 'ASC')->lists('code', 'id');
        $grades = DB::table('grades')->orderBy('created_at', 'ASC')->lists('code', 'id');

        if (auth()->user()->allow("view-all-score-in-all-department")) {
            $departments = DB::table('departments')->where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $user_department_id = null;
        } else {

            $departments = DB::table('departments')
                ->where('id', $employee->department->id)
                ->where("parent_id", config('access.departments.department_academic'))
                ->orderBy("code")->lists("code", "id");
            $user_department_id = $employee->department->id;

        }

        return view('backend.course.courseAnnual.includes.popup_filter_all_score_course_annual', compact(
            'academicYears', 'departments', 'departmentOptions', 'semesters', 'degrees', 'grades', 'user_department_id'
        ));

    }

    public function formScoreAllCourseAnnual(Request $request)
    {


        if (auth()->user()->allow("view-all-score-in-all-department")) {

            $departments = Department::where("parent_id", config('access.departments.department_academic'))->orderBy("code")->lists("code", "id");
            $department_id = null;

        } else {

            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $department_id = $employee->department->id;

        }

        $academicYears = AcademicYear::orderBy("id", "desc")->lists('name_latin', 'id')->toArray();
        $degrees = Degree::lists('name_en', 'id')->toArray();
        $grades = Grade::select([
            'id', DB::raw("CONCAT(code, '-', name_en) as name"
            )])
            ->orderBy('id')
            ->lists('name', 'id')
            ->toArray();

        $semesters = Semester::orderBy('id')->lists('name_en', 'id')->toArray();
        $departmentOptions = DB::table('departmentOptions')->get();


        return view('backend.course.courseAnnual.includes.form_all_score_courses_annual', compact('department_id', 'departments', 'degrees', 'grades', 'academicYears', 'semesters', 'departmentOptions'));

    }

    public function print_total_score(Request $request)
    {
        $data = $this->allHandsontableData($request);
        $data = json_decode($data);

        $academic_year = AcademicYear::where('id', $request->get('academic_year_id'))->first();
        $department = Department::where('id', $request->get('department_id'))->first();
        $degree = Degree::where('id', $request->get('degree_id'))->first();
        $grade = Grade::where('id', $request->get('grade_id'))->first();

        if ($request->get('semester_id') != null) {
            $semester = Semester::where('id', $request->get('semester_id'))->first();
        } else {
            $semester = null;
        }

        if ($request->get('dept_option_id') != null) {
            $dept_option = DepartmentOption::where('id', $request->get('dept_option_id'))->first();
        } else {
            $dept_option = null;
        }

        return view("backend.course.courseAnnual.print.print_total_score",
            compact('data', 'academic_year', 'department', 'degree', 'semester', 'dept_option', 'grade'));
    }

    public function allHandsontableData(Request $request)
    {
        $param = $request->all();
        return $this->get_total_score_summary($param);
    }

    public function get_total_score_summary($param)
    {
        // ------declare reqested data ------

        $academicYearID = $param['academic_year_id'];
        $semesterId = $param['semester_id'];

        $request_group_filter = '';
        //-----------end requested data---------

        //-----declaring variable ------
        $creditInEachSemester = [];// ----credit by each semester ---list by semester_id
        $finalCredit = 0; // ---total credit of course
        $array_data = [];// ---final data to send to view
        $index = 0;// -- count student number
        $average_moyenne_by_semester = [];
        $finalMoynne = []; // get both semesters

        $semesters = DB::table('semesters')->orderBy('semesters.id')->get();

        //-----get headers------

        $headers = $this->getHeadersHandsonTableData($semesterId, $semesters);
        $nestedHeaders = $headers['nested_header'];
        $colWidths = $headers['col_width'];


        //------get course type -------

        $courseAnnuals = $this->getCourseAnnualWithProp($param);
        $array_course_annual_ids = $courseAnnuals->lists('course_annual_id');
        $arrayCourseAnnual = collect($courseAnnuals->get())->groupBy('course_id')->toArray();
        if (count($array_course_annual_ids) == 0) {
            return json_encode([
                'status' => false,
                'data' => [],
                'nestedHeaders' => $nestedHeaders,
                'colWidths' => [],
                'message' => 'The options which you are selected, are not match with the record please check agian!',
                'type' => 'error'
            ]);
        }

        //------get score properties and absence -------

        $allProperties = $this->getCourseAnnualWithScore($array_course_annual_ids);
        $eachCourseAnnualScores = $allProperties['averages'];

        $absences = $allProperties['absences'];

        //---get Selected Group by course annual-----

        $groups = $this->selectedGroupByCourseAnnual($array_course_annual_ids);
        $element = [];
        $totalAbs = [];
        $totalMoyenne = [];
        $each_column_score = [];
        $fail_subjects = [];

        $array_observation = [];
        $array_student_id_card = [];

        if ($arrayCourseAnnual) {

            $status_info_stu = true; // we want to create element array of student name, id-card,sexe just only a time for one course program

            foreach ($arrayCourseAnnual as $course_program_id => $course_Annual) {

                $program = $arrayCourseAnnual[$course_program_id][0];
                // ----merge header and col-width by each course-program------
                if ($program->is_counted_creditability) {
                    $creditInEachSemester[$program->semester_id][] = $program->course_annual_credit;
                }

                if (strlen($program->name_en) > 45) {
                    $course_name = substr($program->name_en, 0, 35) . '...';
                } else {
                    $course_name = $program->name_en;
                }
                $nestedHeaders[0] = array_merge($nestedHeaders[0], [['label' => 'S' . $program->semester_id . '_' . $course_name, 'colspan' => 2]]);
                $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label' => 'Abs', 'colspan' => 1], ['label' => $program->course_annual_credit, 'colspan' => 1]]);
                $colWidths[] = 65;
                $colWidths[] = 65;
                //------end----

                if ($status_info_stu) {

                    $dataHandSontable = $this->manageArrayHandSontableData($array_student_id_card, $course_Annual, $groups, $eachCourseAnnualScores, $element, $status_info_stu, $semesterId, $semesters, $absences, $totalAbs, $totalMoyenne, $each_column_score, $fail_subjects, $array_observation);
                    $status_info_stu = false;

                } else {
                    $dataHandSontable = $this->manageArrayHandSontableData($array_student_id_card, $course_Annual, $groups, $eachCourseAnnualScores, $element, $status_info_stu, $semesterId, $semesters, $absences, $totalAbs, $totalMoyenne, $each_column_score, $fail_subjects, $array_observation);
                }

                if ($dataHandSontable['status']) {
                    $element = $dataHandSontable['element'];
                    $totalAbs = $dataHandSontable['absence'];
                    $totalMoyenne = $dataHandSontable['moyenne'];
                    $each_column_score = $dataHandSontable['each_column_score'];
                    $fail_subjects = $dataHandSontable['fail_subject'];
                    $array_observation = $dataHandSontable['observation'];
                    $array_student_id_card = $dataHandSontable['student_id_card'];

                } else {
                    return json_encode([
                        'status' => false,
                        'data' => [],
                        'nestedHeaders' => $nestedHeaders,
                        'colWidths' => [],
                        'message' => $dataHandSontable['message'],
                        'type' => $dataHandSontable['type']
                    ]);
                }

            }
            //------end foreach of course program
        } else {
            //----there are no course program applying for student yet .....
        }
        //----end if course-program


        $extra_rows = $this->find_max_min_average_mark();
        $data_empty = $extra_rows['data_empty'];
        $max_array = $extra_rows['max'];
        $min_array = $extra_rows['min'];
        $average_array = $extra_rows['average'];

        if ($semesterId) {
            //---additional row for spacing the handsontable

            $data_empty = $data_empty + ['S_' . $semesterId => ""];
            $max_array = $max_array + ['S_' . $semesterId => ""];
            $min_array = $min_array + ['S_' . $semesterId => ""];
            $average_array = $average_array + ['S_' . $semesterId => ""];

            //----------end--

            $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S' . $semesterId . '_Moyenne']);
            $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum(isset($creditInEachSemester[$semesterId]) ? $creditInEachSemester[$semesterId] : [0])]);
        } else {
            foreach ($semesters as $semester) {

                //---------the same as above ...additional row ----

                $data_empty = $data_empty + ['S_' . $semester->id => ""];
                $max_array = $max_array + ['S_' . $semester->id => ""];
                $min_array = $min_array + ['S_' . $semester->id => ""];
                $average_array = $average_array + ['S_' . $semester->id => ""];

                //-----------

                $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S' . $semester->id . '_Moyenne']);
                $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum(isset($creditInEachSemester[$semester->id]) ? $creditInEachSemester[$semester->id] : [0])]);
                $finalCredit = $finalCredit + array_sum(isset($creditInEachSemester[$semester->id]) ? $creditInEachSemester[$semester->id] : [0]);
            }
        }

        // First header
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Moyenne']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Rank']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Redouble']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Rattrapage']);
        //$nestedHeaders[0] = array_merge($nestedHeaders[0], ['Passage']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Remark']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['General Remark']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Observation']);

        // Second header
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [$finalCredit]);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['rank']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['redouble']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['rattrapage']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['remark']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], ['observation']);

//        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;//Rattrapage
//        $colWidths[] = 100;//Passage
        $colWidths[] = 200;//observation
        $colWidths[] = 200;// Remark
        $colWidths[] = 200;// General Remark
        $colWidths[] = 100;//blank header


        $propertiesSemester2 = [];

        foreach ($arrayCourseAnnual as $course_program_id => $course_annual) {

            $tmp_course = $arrayCourseAnnual[$course_program_id][0];
            $array_val = array_values(isset($each_column_score[$course_program_id]) ? $each_column_score[$course_program_id] : [ScoreEnum::Zero]);

            $max = max($array_val);
            $min = min($array_val);
            $aver_rage = (array_sum($array_val)) / count($array_val);

            $data_empty = array_merge($data_empty, ['Abs' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => "", 'Credit' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => ""]);
            $max_array = array_merge($max_array, ['Abs' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => "", 'Credit' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => $this->floatFormat($max)]);
            $min_array = $min_array + ['Abs' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => '', 'Credit' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => $this->floatFormat($min)];
            $average_array = $average_array + ['Abs' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => '', 'Credit' . '_' . ($tmp_course->course_id) . '_' . $tmp_course->semester_id => $this->floatFormat($aver_rage)];

            if (!$semesterId) {
                if ($tmp_course->semester_id == 2) {// additionall colums for radie student in semester 2
                    $propertiesSemester2['Abs_' . $tmp_course->course_id . '_' . $tmp_course->semester_id] = null;
                    $propertiesSemester2['Credit_' . $tmp_course->course_id . '_' . $tmp_course->semester_id] = null;//$this->floatFormat(0);
                }
            }
        }

        $array_tmp_rank = [];

        $objectStudents = $array_student_id_card['object_student'];/*--this object is retrieve from manageArrayHandSontableData function -- */
        $studentProps = $this->student_properties($array_student_id_card['id_card'], $array_student_id_card['student_annual_id'], $academicYearID, $objectStudents);

        $redoubles = $studentProps['redouble'];
        $scholarships = $studentProps['scholarship'];
        $idCardPointToStudent = $studentProps['id_card_to_student'];
        $histories = $studentProps['history'];


        $countElement = collect($element)->map(function ($item, $key) {
            return count($item);
        })->max();


        foreach ($element as $key => $value) {

            $string_observatoin = '';
            $index++;
            $total_number_absences = 0;
            $both_semester = 0;

            if (count($value) < $countElement) {
                foreach ($propertiesSemester2 as $str_index => $prop2) {
                    if (!isset($value[$str_index])) {
                        $value = array_merge($value, [$str_index => $prop2]);
                    }
                }
            }

            //$check_redouble = $this->checkRedouble($idCardPointToStudent[$key], $academicYearID);//---check this current year if student has been change in redouble

            if ($semesterId) {

                $absence_by_semester = isset($totalAbs[$key][$semesterId]) ? array_sum($totalAbs[$key][$semesterId]) : 0;
                $each_course_score = $this->calculateFinalMoyenne(isset($totalMoyenne[$key][$semesterId]) ? $totalMoyenne[$key][$semesterId] : [], isset($creditInEachSemester[$semesterId]) ? $creditInEachSemester[$semesterId] : [1]);//[1] array_one elelement to use array_sum function =1

                $value['S_' . $semesterId] = $absence_by_semester;
                $value['S' . $semesterId . '_Moyenne'] = $each_course_score;

                /*---check to find max , min and average ---- of each column score ---*/
                if ($each_course_score > ScoreEnum::Zero) {
                    $average_moyenne_by_semester[$semesterId][] = $each_course_score;
                }
                $both_semester += $each_course_score;
                $total_number_absences += $absence_by_semester;
                $score_moyenne = $this->floatFormat(($both_semester) / (($finalCredit > 0) ? $finalCredit : 1));
            } else {

                $all_semester_moyenne = collect($totalMoyenne[$key])->collapse()->toArray();
                $all_semester_credit = collect($creditInEachSemester)->collapse()->toArray();
                $score_moyenne = $this->floatFormat(array_sum($all_semester_moyenne) / array_sum($all_semester_credit));

                foreach ($semesters as $semes) {

                    $each_course_score = $this->calculateFinalMoyenne((isset($totalMoyenne[$key][$semes->id]) ? $totalMoyenne[$key][$semes->id] : []), isset($creditInEachSemester[$semes->id]) ? $creditInEachSemester[$semes->id] : [1]);
                    $absence_by_semester = isset($totalAbs[$key][$semes->id]) ? array_sum($totalAbs[$key][$semes->id]) : 0;
                    $value['S_' . $semes->id] = $absence_by_semester;
                    $value['S' . $semes->id . '_Moyenne'] = $each_course_score;

                    if ($each_course_score > ScoreEnum::Zero) {

                        $average_moyenne_by_semester[$semes->id][] = $each_course_score;
                    }
                    $total_number_absences += $absence_by_semester;
                }
            }
            $value['total'] = $total_number_absences;

            if ($score_moyenne > ScoreEnum::Zero) {
                $finalMoynne[] = $score_moyenne;// -----store final column moyenne bye each student to find average max and min\
            }
            $value['Moyenne'] = $score_moyenne;

            $array_tmp_rank[$key] = $score_moyenne;
            $value['Rank'] = "";

            $redouble_objects = $this->findRecordRedouble($idCardPointToStudent[$key], $redouble = isset($redoubles[$key]) ? $redoubles[$key] : null, $academicYearID);
            $value['Redouble'] = $redouble_objects['current_redouble'];
            //---assign number of rattrapage
            $value['Rattrapage'] = '';

            $value['Remark'] = $array_observation[$key]->remark;
            $value['General_Remark'] = $array_observation[$key]->general_remark;

            $string_observatoin .= $redouble_objects['history_redouble'];
            $string_observatoin .= isset($scholarships[$key]) ? $scholarships[$key]->code . ', ' : '';
            $string_observatoin .= isset($histories[$key]) ? $histories[$key]->name_en : '';

            $value['Observation'] = rtrim($string_observatoin, ", ");
            $value[""] = "";// blank column at last
            $value["number"] = $index;
            $element[$key] = $value;
        }

        //-----find student classement

        $array_data = collect($element)->sortByDesc('Moyenne')->toArray();

        $array_data = array_values($array_data);

        $array_data = collect($array_data)->map(function ($data, $key) {
            $data['number'] = $key + 1;
            $data['Rank'] = $key + 1;
            return $data;
        })->toArray();


        //------assign max min average for column s1_moyenne, s2_moyenne and total moyenne

        if (count($average_moyenne_by_semester) > 0) {
            if ($semesterId) {
                $data_empty = array_merge($data_empty, ['S' . $semesterId . '_Moyenne' => ""]);
                $max_array = array_merge($max_array, ['S' . $semesterId . '_Moyenne' => max($average_moyenne_by_semester[$semesterId])]);
                $min_array = array_merge($min_array, ['S' . $semesterId . '_Moyenne' => min($average_moyenne_by_semester[$semesterId])]);
                $average_array = array_merge($average_array, ['S' . $semesterId . '_Moyenne' => $this->floatFormat((array_sum($average_moyenne_by_semester[$semesterId])) / count($average_moyenne_by_semester[$semesterId]))]);
            } else {

                foreach ($semesters as $s) {
                    $score_array = isset($average_moyenne_by_semester[$s->id]) ? $average_moyenne_by_semester[$s->id] : [ScoreEnum::Zero];
                    $data_empty = array_merge($data_empty, ['S' . $s->id => ""]);
                    $max_array = array_merge($max_array, ['S' . $s->id . '_Moyenne' => max($score_array)]);
                    $min_array = array_merge($min_array, ['S' . $s->id . '_Moyenne' => min($score_array)]);
                    $average_array = array_merge($average_array, ['S' . $s->id . '_Moyenne' => $this->floatFormat((array_sum($score_array)) / count($score_array))]);
                }
            }
            $data_empty = array_merge($data_empty, ['Moyenne' => ""]);
            $max_array = array_merge($max_array, ['Moyenne' => max($finalMoynne)]);
            $min_array = array_merge($min_array, ['Moyenne' => min($finalMoynne)]);
            $average_array = array_merge($average_array, ['Moyenne' => $this->floatFormat((array_sum($finalMoynne)) / count($finalMoynne))]);

            $emptyData = $this->addEmptyColData($data_empty, $max_array, $min_array, $average_array);

            $array_data[] = $emptyData['data_empty'];
            $array_data[] = $emptyData['max'];
            $array_data[] = $emptyData['min'];
            $array_data[] = $emptyData['average'];
            $array_data[] = $emptyData['data_empty'];
        }


        return json_encode([
            'array_fail_subject' => $fail_subjects,
            'status' => true,
            'data' => $array_data,
            'nestedHeaders' => $nestedHeaders,
            'colWidths' => $colWidths
        ]);
    }

    private function getHeadersHandsonTableData($semesterId, $semesters)
    {

        $arraySemester = [];
        if ($semesterId) {
            $nestedHeaders = [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label' => 'Absences', 'colspan' => 2]
                ],
                ['', '', '', '',
                    ['label' => 'Total', 'colspan' => 1],
                ]
            ];
            $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label' => 'S_' . $semesterId, 'colspan' => 1]]);
            $colWidths = [50, 80, 220, 50, 60, 55];
        } else {
            $colWidths = [50, 80, 220, 50, 60, 55, 55];
            $nestedHeaders = [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label' => 'Absences', 'colspan' => 3]
                ],
                ['', '', '', '',
                    ['label' => 'Total', 'colspan' => 1],
                ]
            ];
            if ($semesters) {
                foreach ($semesters as $semester) {
                    $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label' => 'S_' . $semester->id, 'colspan' => 1]]);
                    $arraySemester = $arraySemester + ['S_' . $semester->id => 0];
                }
            }
        }

        return ['col_width' => $colWidths, 'nested_header' => $nestedHeaders];
    }

    private function manageArrayHandSontableData($array_student_id_card, $annualCourses, $groups, $eachCourseAnnualScores, $element, $status, $semesterId, $semesters, $absences, $totalAbs, $totalMoyenne, $each_column_score, $fail_subjects, $array_observation)
    {

        //-----loop arrange table from column to row ---


        if ($status) {

            if (count($annualCourses) > 1) {

                foreach ($annualCourses as $eachCourse) {

                    $filtered_students = $this->filtering_student_annual($eachCourse, $groups);

                    if (count($filtered_students) > 0) {

                        foreach ($filtered_students as $stu_dent) {

                            $array_observation[$stu_dent->id_card] = $stu_dent;

                            $each_score = isset($eachCourseAnnualScores[$eachCourse->course_annual_id]) ? (isset($eachCourseAnnualScores[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) ? $this->compareResitScore($eachCourseAnnualScores[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) : 0) : 0;
                            $each_column_score = $this->score_constraint($each_score, $eachCourse, $stu_dent, $each_column_score);
                            $element = $this->init_element($stu_dent, $element);

                            //--------request for only one semester ------
                            $absence_by_course = isset($absences[$eachCourse->course_annual_id]) ? (isset($absences[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) ? $absences[$eachCourse->course_annual_id][$stu_dent->student_annual_id] : null) : null;

                            $each_element_semester = $this->add_element_by_semester($each_score, $semesterId, $semesters, $eachCourse, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs, $array_student_id_card);

                            if ($each_element_semester != false) {

                                $totalMoyenne = $each_element_semester['total_moyenne'];

                                $totalAbs = $each_element_semester['abs'];

                                $element = $each_element_semester['element'];

                                $array_student_id_card = $each_element_semester['student_id_card'];

                            } else {

                                return $this->empty_data(trans('strings.backend.course_annual.wrong_option'), $type = 'warning');
                            }
                            $fail_subjects = $this->generating_student_redouble($eachCourse, $stu_dent, $each_score, $fail_subjects);

                        }


                    } else {
                        $message = '<span style=" font-size: 14pt; color: red;"> ' . $eachCourse->name_en . '</span>' . trans('strings.backend.course_annual.no_student_record');
                        return $this->empty_data($message, $type = 'warning');
                    }
                }

            } else {
                //---course-program and course-annual are the same (one to one)

                foreach ($annualCourses as $course) {
                    $tmpCourse = $course;
                    $filtered_students = $this->filtering_student_annual($course, $groups);
                }


                if (count($filtered_students) > 0) {

                    foreach ($filtered_students as $stu_dent) {
                        $array_observation[$stu_dent->id_card] = $stu_dent;
                        //-----$annualCourses[0] this array contains only one course annual that which this course is the same withe course program
                        $absence_by_course = isset($absences[$tmpCourse->course_annual_id]) ? (isset($absences[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) ? $absences[$tmpCourse->course_annual_id][$stu_dent->student_annual_id] : null) : null;
                        $each_score = isset($eachCourseAnnualScores[$tmpCourse->course_annual_id]) ? (isset($eachCourseAnnualScores[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) ? $this->compareResitScore($eachCourseAnnualScores[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) : 0) : 0;
                        $each_column_score = $this->score_constraint($each_score, $tmpCourse, $stu_dent, $each_column_score);
                        $element = $this->init_element($stu_dent, $element);

                        //--------request for only one semester ------

                        $each_element_semester = $this->add_element_by_semester($each_score, $semesterId, $semesters, $tmpCourse, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs, $array_student_id_card);
                        if ($each_element_semester != false) {
                            $totalMoyenne = $each_element_semester['total_moyenne'];
                            $totalAbs = $each_element_semester['abs'];
                            $element = $each_element_semester['element'];
                            $array_student_id_card = $each_element_semester['student_id_card'];

                        } else {
                            return $this->empty_data(trans('strings.backend.course_annual.wrong_option'), $type = 'warning');
                        }
                        $fail_subjects = $this->generating_student_redouble($tmpCourse, $stu_dent, $each_score, $fail_subjects);

                    }
                } else {
                    $message = '<span style=" font-size: 14pt; color: red;"> ' . $tmpCourse->name_en . '</span>' . trans('strings.backend.course_annual.no_student_record');
                    return $this->empty_data($message, $type = 'warning');
                }


            }
        } else {

            if (count($annualCourses) > 1) {


                foreach ($annualCourses as $eachCourse) {

                    $filtered_students = $this->filtering_student_annual($eachCourse, $groups);

                    if (count($filtered_students) > 0) {

                        foreach ($filtered_students as $stu_dent) {

                            $array_observation[$stu_dent->id_card] = $stu_dent;
                            $absence_by_course = isset($absences[$eachCourse->course_annual_id]) ? (isset($absences[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) ? $absences[$eachCourse->course_annual_id][$stu_dent->student_annual_id] : null) : null;

                            $each_score = isset($eachCourseAnnualScores[$eachCourse->course_annual_id]) ? (isset($eachCourseAnnualScores[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) ? $this->compareResitScore($eachCourseAnnualScores[$eachCourse->course_annual_id][$stu_dent->student_annual_id]) : 0) : 0;

                            $each_column_score = $this->score_constraint($each_score, $eachCourse, $stu_dent, $each_column_score);

                            $each_element_semester = $this->concate_element_by_semester($each_score, $semesterId, $semesters, $eachCourse, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs);
                            if ($each_element_semester == false) {
                                return $this->empty_data(trans('strings.backend.course_annual.wrong_option'), $type = 'warning');
                            } else {
                                $totalMoyenne = $each_element_semester['total_moyenne'];
                                $totalAbs = $each_element_semester['abs'];
                                $element = $each_element_semester['element'];
                            }

                            $fail_subjects = $this->generating_student_redouble($eachCourse, $stu_dent, $each_score, $fail_subjects);

                        }
                    } else {

                        $message = '<span style=" font-size: 14pt; color: red;"> ' . $eachCourse->name_en . '</span>' . trans('strings.backend.course_annual.no_student_record');
                        return $this->empty_data($message, $type = 'warning');
                    }
                }


            } else {

                //---course-program and course-annual are the same (one to one)

                foreach ($annualCourses as $eachCourse) {
                    $tmpCourse = $eachCourse;
                    $filtered_students = $this->filtering_student_annual($eachCourse, $groups);
                }

                if (count($filtered_students) > 0) {

                    foreach ($filtered_students as $stu_dent) {
                        //-----$annualCourses[0] this array contains only one course annual that which this course is the same withe course program

                        $array_observation[$stu_dent->id_card] = $stu_dent;
                        $absence_by_course = isset($absences[$tmpCourse->course_annual_id]) ? (isset($absences[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) ? $absences[$tmpCourse->course_annual_id][$stu_dent->student_annual_id] : null) : null;
                        $each_score = isset($eachCourseAnnualScores[$tmpCourse->course_annual_id]) ? (isset($eachCourseAnnualScores[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) ? $this->compareResitScore($eachCourseAnnualScores[$tmpCourse->course_annual_id][$stu_dent->student_annual_id]) : 0) : 0;

                        $each_column_score = $this->score_constraint($each_score, $tmpCourse, $stu_dent, $each_column_score);

                        $each_element_semester = $this->concate_element_by_semester($each_score, $semesterId, $semesters, $tmpCourse, $stu_dent, $element, $absence_by_course, $totalMoyenne, $totalAbs);
                        if ($each_element_semester == false) {
                            return $this->empty_data(trans('strings.backend.course_annual.wrong_option'), $type = 'warning');
                        } else {
                            $totalMoyenne = $each_element_semester['total_moyenne'];
                            $totalAbs = $each_element_semester['abs'];
                            $element = $each_element_semester['element'];
                        }

                        $fail_subjects = $this->generating_student_redouble($tmpCourse, $stu_dent, $each_score, $fail_subjects);

                    }
                } else {
                    $message = '<span style=" font-size: 14pt; color: red;"> ' . $tmpCourse->name_en . '</span>' . trans('strings.backend.course_annual.no_student_record');
                    return $this->empty_data($message, $type = 'info');
                }


            }
        }
        return [
            'status' => true,
            'student_id_card' => $array_student_id_card,
            'element' => $element,
            'absence' => $totalAbs,
            'moyenne' => $totalMoyenne,
            'each_column_score' => $each_column_score,
            'fail_subject' => $fail_subjects,
            'observation' => $array_observation
        ];
    }

    /*
     * @params Request $request
     * @params dept_id,
     * @params degree_id,
     * @params grade_id,
     * @params academic_year_id,
     * @params semester_id,
     * @params dept_option_id
     * @return view
     */

    private function find_max_min_average_mark()
    {

        $dataEmpty = [
            'number' => "",
            'student_id_card' => "",
            'student_name' => "",
            'student_gender' => "",
            'total' => "",
        ]; // use to make one more row space ..

        $maxArray = [
            'number' => "",
            'student_id_card' => "",
            'student_name' => "MAX",
            'student_gender' => "",
            'total' => "",
        ];
        $minArray = [
            'number' => "",
            'student_id_card' => "",
            'student_name' => "MIN",
            'student_gender' => "",
            'total' => "",
        ];
        $averageArray = [
            'number' => "",
            'student_id_card' => "",
            'student_name' => "MOYENNE",
            'student_gender' => "",
            'total' => "",
        ];

        return [
            'data_empty' => $dataEmpty,
            'min' => $minArray,
            'max' => $maxArray,
            'average' => $averageArray
        ];

    }

    private function calculateFinalMoyenne($arrayScore, $array_totalCreditBySemester)
    {
        $allScore = array_sum($arrayScore);
        $totalCredit = array_sum($array_totalCreditBySemester);
        $score = (($allScore) / (($totalCredit > ScoreEnum::Zero) ? $totalCredit : ScoreEnum::One));
        return $this->floatFormat($score);
    }

    private function addEmptyColData($data_empty, $max_array, $min_array, $average_array)
    {

        $data_empty = array_merge($data_empty, ['Rank' => ""]);
        $data_empty = array_merge($data_empty, ['Redouble' => ""]);
        $data_empty = array_merge($data_empty, ['Rattrapage' => ""]);
//        $data_empty = array_merge($data_empty,['Passage' => ""]);
        $data_empty = array_merge($data_empty, ['Remark' => ""]);
        $data_empty = array_merge($data_empty, ['Observation' => ""]);
        $data_empty = array_merge($data_empty, ['' => ""]);

        $max_array = array_merge($max_array, ['Rank' => ""]);
        $max_array = array_merge($max_array, ['Redouble' => ""]);
        $max_array = array_merge($max_array, ['Rattrapage' => ""]);
//        $max_array = array_merge($max_array,['Passage' => ""]);
        $max_array = array_merge($max_array, ['Remark' => ""]);
        $max_array = array_merge($max_array, ['Observation' => ""]);
        $max_array = array_merge($max_array, ['' => ""]);

        $min_array = array_merge($min_array, ['Rank' => ""]);
        $min_array = array_merge($min_array, ['Redouble' => ""]);
        $min_array = array_merge($min_array, ['Rattrapage' => ""]);
//        $min_array = array_merge($min_array,['Passage' => ""]);
        $min_array = array_merge($min_array, ['Remark' => ""]);
        $min_array = array_merge($min_array, ['Observation' => ""]);
        $min_array = array_merge($min_array, ['' => ""]);

        $average_array = array_merge($average_array, ['Rank' => ""]);
        $average_array = array_merge($average_array, ['Redouble' => ""]);
        $average_array = array_merge($average_array, ['Rattrapage' => ""]);
//        $average_array = array_merge($average_array,['Passage' => ""]);
        $average_array = array_merge($average_array, ['Remark' => ""]);
        $average_array = array_merge($average_array, ['Observation' => ""]);
        $average_array = array_merge($average_array, ['' => ""]);

        return [
            'data_empty' => $data_empty,
            'max' => $max_array,
            'min' => $min_array,
            'average' => $average_array
        ];
    }

    public function switchCourseAnnual(Request $request)
    {
        return $this->handsonTableData($request->course_annual_id, $request->group_id);
    }

    public function saveEachCellNotationCourseAnnual(Request $request)
    {
        $input = [
            'course_annual_id' => $request->course_annual_id,
            'student_annual_id' => $request->student_annual_id,
            'notation' => $request->description
        ];
        $find = $this->absences->findAbsenceByCourseAndStudent($input['course_annual_id'], $input['student_annual_id']);

        if ($find) {
            $update = $this->absences->update($find->id, $input);

            if ($update) {
                return Response::json(['status' => true]);
            }

        } else {
            $storeNotation = $this->absences->create($input);
            if ($storeNotation) {
                return Response::json(['status' => true]);
            }
        }
    }

    public function exportCourseScore(Request $request)
    {
        $studentListScore = [];
        $colHeaders = explode(',', $request->col_headers);
        $courseAnnual = $this->courseAnnuals->findOrThrowException($request->course_annual_id);
        $department = Department::where('id', $courseAnnual->department_id)->first();
        $allScoreByCourseAnnual = $this->studentScoreCourseAnnually($courseAnnual);

        $propArrayIds = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnual);
        $groups = $propArrayIds['group'];
        $department_ids = $propArrayIds['department_id'];
        $degree_ids = $propArrayIds['degree_id'];
        $grade_ids = $propArrayIds['grade_id'];
        $department_option_ids = $propArrayIds['department_option_id'];


        $allNumberAbsences = $this->getAbsenceFromDB($request->course_annual_id);
        $studentNotations = $allNumberAbsences;

        $students = $this->getStudentByDeptIdGradeIdDegreeId($department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id);

        if (count($department_option_ids) > 0) {
            $students = $students->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }

        if (($group = $request->group_id) && $request->group_id != 'undefined') {

            $studentAnnualIds = DB::table('group_student_annuals')
                ->whereIn('group_id', [$group])
                ->where('semester_id', $courseAnnual->semester_id);

            if ($department->is_vocational) {

                $studentAnnualIds = $studentAnnualIds->where('group_student_annuals.department_id', '=', $courseAnnual->department_id)->lists('student_annual_id');
            } else {

                $studentAnnualIds = $studentAnnualIds
                    ->whereNull('group_student_annuals.department_id')
                    ->lists('student_annual_id');
            }

            $students = $students->whereIn('studentAnnuals.id', $studentAnnualIds);

            if ($courseAnnual->semester_id > 1) {

                $students = $students
                    ->where(function ($query) {
                        $query->where('students.radie', '=', false)
                            ->orWhereNull('students.radie');
                    })
                    ->orderBy('students.name_latin')->get();

            } else {
                $students = $students->orderBy('students.name_latin')->get();
            }
        } else {

            if ($groups) {

                $studentAnnualIds = DB::table('group_student_annuals')
                    ->whereIn('group_id', $groups)
                    ->where('semester_id', $courseAnnual->semester_id);

                if ($department->is_vocational) {

                    $studentAnnualIds = $studentAnnualIds->where('group_student_annuals.department_id', '=', $courseAnnual->department_id)->lists('student_annual_id');

                } else {
                    $studentAnnualIds = $studentAnnualIds
                        ->whereNull('group_student_annuals.department_id')
                        ->lists('student_annual_id');
                }


                $students = $students->whereIn('studentAnnuals.id', $studentAnnualIds)->orderBy('students.name_latin');

                if ($courseAnnual->semester_id > SemesterEnum::SEMESTER_ONE) {

                    $students = $students
                        ->where(function ($query) {
                            $query->where('students.radie', '=', false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin')->get();

                } else {
                    $students = $students->orderBy('students.name_latin')->get();
                }

            } else {

                if ($courseAnnual->semester_id > SemesterEnum::SEMESTER_ONE) {

                    $students = $students
                        ->where(function ($query) {
                            $query->where('students.radie', '=', false)
                                ->orWhereNull('students.radie');
                        })
                        ->orderBy('students.name_latin')->get();

                } else {
                    $students = $students->orderBy('students.name_latin')->get();
                }

            }
        }

        $listStudentIds = collect($students)->pluck('student_id')->toArray();
        $redoubleStudentObjects = $this->redoubleByStudentIds($listStudentIds, $courseAnnual->academic_year_id);

        usort($students, function ($a, $b) {
            return strcmp(strtolower($a->name_latin), strtolower($b->name_latin));
        });

        foreach ($students as $student) {

            if (!isset($redoubleStudentObjects[$student->student_id])) {

                $totalScore = 0;

                $studentScores = isset($allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id]) ? $allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id] : [];

                if ($courseAnnual->is_counted_absence) {
                    $scoreAbsence = isset($allNumberAbsences[$courseAnnual->id][$student->student_annual_id]) ? $allNumberAbsences[$courseAnnual->id][$student->student_annual_id] : null;// get number of absence from database
                    //--calculate score absence to sum with the real score
                    $totalCourseHours = ($courseAnnual->time_course + $courseAnnual->time_tp + $courseAnnual->time_td);
                    $scoreAbsenceByCourse = number_format((float)((($totalCourseHours) - (isset($scoreAbsence) ? $scoreAbsence->num_absence : 0)) * 10) / ((($totalCourseHours != 0) ? $totalCourseHours : 1)), 2, '.', '');
                    $totalScore = $totalScore + (($scoreAbsenceByCourse >= 0) ? $scoreAbsenceByCourse : 0);
                }

                if ($studentScores) {
                    foreach ($studentScores as $score) {

                        if (is_numeric($score->score)) {
                            $totalScore = $totalScore + ($score->score);// calculate score for stuent annual
                            $scoreData[$score->name] = (($score->score != null) ? $score->score : null);
                        } else {
                            $scoreData[$score->name] = (($score->score != null) ? $score->score : null);
                        }

                    }
                } else {

                    $scoreData = [];
                }

                if ($courseAnnual->is_counted_absence) {
                    $element = [
                        "Student ID" => $student->id_card,
                        "Student Name" => strtoupper($student->name_latin),
                        "M/F" => $student->code,
                        "Abs" => ($scoreAbsence) ? $scoreAbsence->num_absence : 0,
                        "Abs-10%" => $scoreAbsenceByCourse,
                    ];
                } else {

                    $element = [
                        "Student ID" => $student->id_card,
                        "Student Name" => $student->name_latin,
                        "M/F" => $student->code
                    ];
                }
                $element = $element + $scoreData + ["Total" => $totalScore, "Notation" => isset($studentNotations[$student->student_annual_id]) ? $studentNotations[$student->student_annual_id]->notation : ''];

                $studentListScore[] = $element;

            }
        }
        $title = 'Student_Score_Lists';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }


        Excel::create($title, function ($excel) use ($studentListScore, $title, $alpha, $colHeaders) {

            $excel->sheet($title, function ($sheet) use ($studentListScore, $title, $alpha, $colHeaders) {
                $sheet->fromArray($studentListScore);
            });

        })->download('xls');
    }

    public function formImportScore(Request $request)
    {

        if ($request->group_id) {
            $group = $request->group_id;
        } else {
            $group = null;
        }
        $courseAnnual = $this->courseAnnuals->findOrThrowException($request->course_annual_id);
        return view('backend.course.courseAnnual.includes.popup_import_score_file', compact('courseAnnual', 'group'));
    }

    public function importScore($courseAnnualId, Request $request)
    {

        $isError = false;
        $errorNumberAbsence = false;
        $isStringAllowed = false;
        $isNotAceptedScore = false;
        $countStudentScoreType = 1;
        $ifScoreImported = 0;
        $headerPercentage = 0;
        $ifAbsenceUpdated = 0;
        $ifAbsenceCreated = 0;
        $studentAbsenceIdError = [];
        $isFileHasColumnScoreType = [];
        $arrayMissedStudent = [];
        $arrayDataUploaded = [];
        $colHeader = '';

        $courseAnnual = CourseAnnual::where('id', $courseAnnualId)->first();

        if ($request->file('import') != null) {
            $import = "score" . '_' . Carbon::now()->getTimestamp() . '.' . $request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/course_annuals/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/course_annuals/' . $import;
            $score_property = $this->getScoreId($courseAnnualId);
            $students = $this->getStudentByNameAndIdCard($courseAnnual, $request->group_id, $score_property['student_annual_id']);
            $scoreIds = $score_property['score_by_student'];
            $percentage = $this->getPercentage($score_property['score_id']);
            $notations = $this->getStudentAbsence($courseAnnualId); // there is a field notation in table absence

            //$averageByCourse =  $this->averageByCourseAnnual($courseAnnualId);

//            ----if count not count 10% absence
            if ($courseAnnual->is_counted_absence) {
                $absences = $notations;

            } else {
                $absences = null;
            }


            DB::beginTransaction();
            try {
                Excel::load($storage_path, function ($reader) use (&$isError) {
                    $firstrow = $reader->first()->toArray();

                    if ((!isset($firstrow['student_id'])) || (!isset($firstrow['student_name'])) || (count($firstrow) < 3)) {
                        $isError = true;
                    }
                });

                if ($isError) {
                    return redirect()->back()->with(['status' => 'Problem with no data in the first row, or your file misses some fields. To make file corrected please export the template!!']);
                }

                Excel::load($storage_path, function ($results)
                use (&$isError, &$arrayDataUploaded, $percentage, $students, $courseAnnualId, $scoreIds, $courseAnnual, $absences, $notations) {
                    $arrayDataUploaded = $results->all()->toArray();

                });
                // $totalCourseHours = $courseAnnual->time_td + $courseAnnual->time_tp + $courseAnnual->time_course;
                foreach ($arrayDataUploaded as $row) {


                    /*----here if we want to calculate score and store in average table ----*/
                    /*
                      $totalScore =0;
                      $scoreAbsence = $this->floatFormat(((($totalCourseHours) - (isset($row['abs']) ? $row['abs'] : 0)) * ScoreEnum::Score_10) / ((($totalCourseHours != 0) ? $totalCourseHours : 1)));

                      if($courseAnnual->is_counted_absence) {

                          if(collect($percentage)->first() == ScoreEnum::STR_MID_30) {

                              $totalScore = $row[strtolower(ScoreEnum::STR_MID_30)] + $row['final_60'];

                          } else if(collect($percentage)->first() == ScoreEnum::STR_MID_40) {

                              $totalScore = $row[strtolower(ScoreEnum::STR_MID_40)] + $row['final_50'];

                          } else if(collect($percentage)->first() == ScoreEnum::STR_FIN_90) {

                              $totalScore = $row[strtolower(ScoreEnum::STR_FIN_90)];
                          }
                          $totalScore += $scoreAbsence;
                      } else {

                          $totalScore = $row[strtolower(ScoreEnum::STR_FIN_100)];
                      }

                      $input = [
                          'course_annual_id' => $courseAnnual->id,
                          'student_annual_id' => $students[$row['student_id']]->student_annual_id,
                          'average' => $this->floatFormat($totalScore)
                      ];
                      if(isset($averageByCourse[$courseAnnualId][$students[$row['student_id']]->student_annual_id])) {
                          $this->createOrUpdateTotalScoreCourseAnnual($input, $averageByCourse[$courseAnnualId][$students[$row['student_id']]->student_annual_id],$courseAnnual);
                      } else {
                          $this->createOrUpdateTotalScoreCourseAnnual($input, [],$courseAnnual);
                      }*/


                    /*----end calculate total score of each course and storing ------*/


                    if (isset($students[$row['student_id']])) {
                        //-----here we knew student has already intial the score of this course..so the upload file must contain students whom Id_card march with the record of score from DB

                        if (isset($scoreIds[$students[$row['student_id']]->student_annual_id])) {
                            $studentScoreIds = $scoreIds[$students[$row['student_id']]->student_annual_id];
                        } else {
                            $studentScoreIds = [];//----do nothing
                        }
                    } else {
                        $studentScoreIds = [];
                    }

                    if (count($studentScoreIds) > 0) {

                        $countStudentScoreType = count($studentScoreIds);

                        foreach ($studentScoreIds as $scoreId) {

                            $colName = $this->convertColumnName($percentage[$scoreId->id]);
                            if (array_key_exists($colName, $row)) { // check the array key of score name

                                if ((($row[$colName] == null) || is_numeric($row[$colName])) || (($row[$colName] == ScoreEnum::Absence) || ($row[$colName] == ScoreEnum::Fraud))) {

                                    $explode = explode('_', $colName);
                                    $percent = $explode[count($explode) - 1];

                                    if ((((float)$row[$colName] <= (float)$percent) && ((float)$row[$colName] >= 0))) {
                                        $input = [
                                            'score' => $row[$colName]
                                        ];
                                        $score = $this->courseAnnualScores->update($scoreId->id, $input);

                                        if ($score) {
                                            $ifScoreImported++;
                                        }
                                    } else {
                                        $isNotAceptedScore = true;
                                        $headerPercentage = $percent;
                                        $colHeader = $colName;
                                        DB::rollback();
                                        break;
                                    }

                                } else {
                                    // score value is exactly the string so we must not accept it
                                    $isStringAllowed = true;
                                }
                            } else {
                                $isFileHasColumnScoreType[$colName] = $colName;
                            }
                        }

                        // Absence score is included
                        if ($courseAnnual->is_counted_absence) {

                            if (is_numeric($row['abs']) || ((trim($row['abs']) == null) || (trim($row['abs']) == ''))) { // ---absence column

                                if (trim($row['abs']) == null || trim($row['abs']) == '') {
                                    $row['abs'] = null;
                                }

                                if (((float)($row['abs'] <= ($courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp)) && ((float)$row['abs'] >= 0))) {

                                    if (isset($absences[$students[$row['student_id']]->student_annual_id])) {

                                        $absence = $absences[$students[$row['student_id']]->student_annual_id];

                                        if ($absence) {
                                            //----update student absence
                                            $input = [
                                                'num_absence' => $row['abs'],
                                                'notation' => isset($row['notation']) ? $row['notation'] : null
                                            ];
                                            $update = $this->absences->update($absence->id, $input);
                                            if ($update) {

                                                $ifAbsenceUpdated++;
                                            }
                                        }
                                    } else {

                                        //----create student absence
                                        $input = [
                                            'course_annual_id' => $courseAnnualId,
                                            'student_annual_id' => $students[$row['student_id']]->student_annual_id,
                                            'num_absence' => $row['abs'],
                                            'notation' => isset($row['notation']) ? $row['notation'] : null
                                        ];
                                        $store = $this->absences->create($input);
                                        if ($store) {
                                            $ifAbsenceCreated++;
                                        }
                                    }
                                } else {

                                    $studentAbsenceIdError[] = $row;
                                    // the absence value is not in the conditioin
                                    $errorNumberAbsence = true;

                                    DB::rollback();
                                }

                            } else {
                                // the absence value is exactly string
                                $studentAbsenceIdError[] = $row;
                                $errorNumberAbsence = true;
                                DB::rollback();
                            }
                        } else {

                            if (isset($row['notation'])) { // ---notation column

                                if (isset($notations[$students[$row['student_id']]->student_annual_id])) {

                                    $notation = $notations[$students[$row['student_id']]->student_annual_id];

                                    if ($notation) {
                                        //----update student notation
                                        $input = [
                                            'course_annual_id' => $notation->course_annual_id,
                                            'student_annual_id' => $notation->student_annual_id,
                                            'notation' => $row['notation']
                                        ];

                                        $update = $this->absences->update($notation->id, $input);
                                    }
                                } else {
                                    //----create student absence
                                    $input = [
                                        'course_annual_id' => $courseAnnualId,
                                        'student_annual_id' => $students[$row['student_id']]->student_annual_id,
                                        'notation' => $row['notation']
                                    ];
                                    $store = $this->absences->create($input);
                                }
                            }
                        }

                    } else {
                        //----here if the score ids of student does not exit ...it mean they miss out the student id-card or this student does not exist in our System
                        $arrayMissedStudent[] = $row;
                    }
                }

                if (file_exists($storage_path)) {
                    unlink($storage_path);
                }
                if ($isNotAceptedScore) {
                    return redirect()->back()->with(['status' => $colHeader . ' score must be between 0 and ' . $headerPercentage . ', no string allowed!']);
                }
                if ($isStringAllowed) {
                    return redirect()->back()->with(['status' => 'No string allowed!']);
                }
                if (count($isFileHasColumnScoreType) > 0) {
                    $string = ' ';
                    foreach ($isFileHasColumnScoreType as $scoreType) {
                        $string = $string . $scoreType . ' ';
                    }
                    return redirect()->back()->with(['status' => 'Your file does not have this field score: ' . $string . ' Please export template as sample!']);
                }
                if ($errorNumberAbsence) {

                    $error_ids = $studentAbsenceIdError;
                    if (count($error_ids) > 1) {
                        $str = ' Error ' . 'IDs: ';
                        $str_special = '';

                        foreach ($error_ids as $error) {
                            $str_special = $str_special . $error['student_id'] . ' Col :[ABS = ' . $error['abs'] . ']' . ', ';
                        }

                        $str = $str . rtrim($str_special, ',');
                    } else {
                        $str = ' Error ' . 'ID: ' . $error_ids[0]['student_id'] . ' Col :[ABS= ' . $error_ids[0]['abs'] . ']';
                    }
                    $string = 'The absence value must be between 0 and ' . ($courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp) . ', No string allowed!' . $str;

                    return redirect()->back()->with(['status' => htmlspecialchars($string)]);
                }
            } catch (Exception $e) {

                DB::rollback();
            }

            DB::commit();

            if (count($arrayMissedStudent) > 0) {

                //return redirect(route('admin.score.success_imported', $courseAnnualId))->with(['status_student' => $arrayMissedStudent]);
                return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status_student' => $arrayMissedStudent]);
            } else {

                if ($courseAnnual->is_counted_absence) {

                    //dd((($ifScoreImported / $countStudentScoreType) .'=='. count($students)) .'||'. (($ifAbsenceUpdated + $ifAbsenceCreated) .'=='. count($students)));

                    if ((($ifScoreImported / $countStudentScoreType) == count($students)) || (($ifAbsenceUpdated + $ifAbsenceCreated) == count($students))) {

                        //return redirect(route('admin.score.success_imported', $courseAnnualId))->with(['status' => 'File Imported']);
                        return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status' => 'File Imported']);
                    } else {

                        if ((($ifScoreImported / $countStudentScoreType) < count($students))) {

                            // return redirect(route('admin.score.success_imported', $courseAnnualId))->with(['status' => 'File Imported. Note There are some usless score record. Please Ask Admin to delete them']);
                            return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status' => 'File Imported. Note There are some usless score record. Please Ask Admin to delete them']);
                        }

                    }
                } else {
                    if ((($ifScoreImported / $countStudentScoreType) == count($students))) {

                        //return redirect(route('admin.score.success_imported', $courseAnnualId))->with(['status' => 'File Imported']);
                        return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status' => 'File Imported']);

                    } else {
                        return redirect()->back()->with(['status' => 'Something went wrong']);
                    }
                }
            }
        } else {
            return redirect()->back()->with(['status' => 'Please Select File!']);
        }
    }

    private function getScoreId($courseAnnualId)
    {

        $scores = DB::table('scores')
            ->where('scores.course_annual_id', $courseAnnualId)->get();

        $scoreCollection = collect($scores);

        $studentAnnualIds = $scoreCollection->pluck('student_annual_id')->unique()->toArray();
        $studentAnnualIds = array_values($studentAnnualIds);

        $scoreIds = $scoreCollection->pluck('id')->toArray();
        $scores = $scoreCollection->groupBy('student_annual_id')->toArray();

        return [
            'student_annual_id' => $studentAnnualIds,
            'score_by_student' => $scores,
            'score_id' => $scoreIds
        ];
    }

    private function getStudentByNameAndIdCard($courseAnnual, $request_group, $studentAnnualIds)
    {
        $selects = [
            'studentAnnuals.id as student_annual_id',
            'students.name_latin',
            'students.name_kh', 'students.radie',
            'students.id_card', 'students.id as student_id',
            'studentAnnuals.academic_year_id',
            'studentAnnuals.department_id',
            'studentAnnuals.department_option_id',
            'studentAnnuals.degree_id',
            'students.observation',
            'studentAnnuals.remark',
            'studentAnnuals.general_remark', 'studentAnnuals.history_id'
        ];

        $studentAnnuals = DB::table('students')
            ->join('studentAnnuals', function ($query) use ($studentAnnualIds) {
                $query->on('studentAnnuals.student_id', '=', 'students.id')
                    ->whereIn('studentAnnuals.id', $studentAnnualIds);
            })->select($selects)->orderBy('name_latin')->get();

        return collect($studentAnnuals)->keyBy('id_card')->toArray();
    }

    private function getPercentage($scoreIds)
    {
        $percentages = DB::table('percentages')
            ->join('percentage_scores', 'percentage_scores.percentage_id', '=', 'percentages.id')
            ->whereIn('percentage_scores.score_id', $scoreIds)->get();

        $percentages = collect($percentages)->keyBy('score_id')->toArray();

        return ($percentages);
    }

    private function convertColumnName($object)
    {
        $trim = trim($object->name, '%');
        $strReplace = str_replace("-", "_", $trim);
        return strtolower($strReplace);
    }

    public function getGroupByCourseAnnual(Request $request)
    {

        $courseAnnual = DB::table('course_annuals')->where('id', $request->course_annual_id)->first();

        $allGroups = DB::table('course_annual_classes')
            ->where([
                ['course_annual_id', $courseAnnual->id],
                ['course_session_id', null]
            ])
            ->whereNotNull('group_id');

        if (count($allGroups->get()) > 0) {

            $allGroups = $allGroups->join('groups', function ($groupQuery) {
                $groupQuery->on('course_annual_classes.group_id', '=', 'groups.id');

            })->orderBy('groups.code', 'ASCE')->get();

        } else {

            $studentAnnualIds = DB::table('studentAnnuals')
                ->where([
                    ['academic_year_id', $courseAnnual->academic_year_id],
                    ['degree_id', $courseAnnual->degree_id],
                    ['grade_id', $courseAnnual->grade_id],
                ]);

            if ($courseAnnual->department_option_id) {
                $studentAnnualIds = $studentAnnualIds->where('department_option_id', $courseAnnual->department_option_id);
            }

            if ($courseAnnual->responsible_department_id) {
                $department = Department::where('id', $courseAnnual->responsible_department_id)->first();
                if ($department->is_vocational) {
                    $studentAnnualIds = $studentAnnualIds->lists('id');

                    $allGroups = DB::table('group_student_annuals')
                        ->join('groups', function ($query) use ($studentAnnualIds, $courseAnnual) {
                            $query->on('group_student_annuals.group_id', '=', 'groups.id')
                                ->where('group_student_annuals.semester_id', '=', $courseAnnual->semester_id)
                                ->where('group_student_annuals.department_id', '=', $courseAnnual->responsible_department_id)
                                ->whereIn('group_student_annuals.student_annual_id', $studentAnnualIds);
                        })
                        ->select('groups.id as id', 'groups.code as code', 'group_student_annuals.department_id as group_department_id')
                        ->groupBy('groups.id', 'group_department_id')
                        ->orderBy('code')->get();
                } else {

                    $studentAnnualIds = $studentAnnualIds->where('department_id', $courseAnnual->department_id);
                    $studentAnnualIds = $studentAnnualIds->lists('id');

                    $allGroups = DB::table('group_student_annuals')
                        ->join('groups', function ($query) use ($studentAnnualIds, $courseAnnual) {
                            $query->on('group_student_annuals.group_id', '=', 'groups.id')
                                ->where('group_student_annuals.semester_id', '=', $courseAnnual->semester_id)
                                ->whereNull('group_student_annuals.department_id')
                                ->whereIn('group_student_annuals.student_annual_id', $studentAnnualIds);
                        })
                        ->select('groups.id as id', 'groups.code as code', 'group_student_annuals.department_id as group_department_id')
                        ->groupBy('groups.id', 'group_department_id')
                        ->orderBy('code')->get();
                }
            } else {
                $studentAnnualIds = $studentAnnualIds->where('department_id', $courseAnnual->department_id);
                $studentAnnualIds = $studentAnnualIds->lists('id');

                $allGroups = DB::table('group_student_annuals')
                    ->join('groups', function ($query) use ($studentAnnualIds, $courseAnnual) {
                        $query->on('group_student_annuals.group_id', '=', 'groups.id')
                            ->where('group_student_annuals.semester_id', '=', $courseAnnual->semester_id)
                            ->whereNull('group_student_annuals.department_id')
                            ->whereIn('group_student_annuals.student_annual_id', $studentAnnualIds);
                    })
                    ->select('groups.id as id', 'groups.code as code', 'group_student_annuals.department_id as group_department_id')
                    ->groupBy('groups.id', 'group_department_id')
                    ->orderBy('code')->get();
            }
        }

        usort($allGroups, function ($a, $b) {
            if (is_numeric($a->code)) {
                return $a->code - $b->code;
            } else {
                return strcmp($a->code, $b->code);
            }
        });
        $groups = $allGroups;
        if ($groups) {
            return view('backend.course.courseSession.group_by_course_session_selection', compact('groups'));
        }

    }

    public function toggle_scoring(ToggleScoringCourseAnnualRequest $request, $id)
    {
        if ($request->ajax()) {
            $course_annual = CourseAnnual::find($id);
            $operation = $request->get('operation');
            if ($operation == null || $operation == "") {
                $operation = "no";
            }

            $course_annual->is_allow_scoring = $operation;

            if ($course_annual->save()) {
                return \Illuminate\Support\Facades\Response::json(array("success" => true, "message" => "Operation is successful."));
            } else {
                return \Illuminate\Support\Facades\Response::json(array("success" => false, "message" => "Something went wrong."));
            }
        }
    }

    public function enable_scoring(ToggleScoringCourseAnnualRequest $request)
    {
        if ($request->ajax()) { // Only accept through ajax
            if ($this->mass_toggle_scoring($request, "yes")) {
                return \Illuminate\Support\Facades\Response::json(array("success" => true, "message" => "All given courses are allowed for scoring."));
            } else {
                return \Illuminate\Support\Facades\Response::json(array("success" => false, "message" => "Something went wrong."));
            }
        }
    }

    private function mass_toggle_scoring(ToggleScoringCourseAnnualRequest $request, $status)
    {
        $course_annuals = CourseAnnual::where('academic_year_id', $request->get('academic_year'));

        // Select department
        if (Auth::user()->allow("disable-enable-input-score-into-course-annual-in-all-department")) {
            if ($request->get('department') != null && $request->get('department') != "") {
                $course_annuals = $course_annuals->where('department_id', $request->get('department'));
            }
        } else {
            // This is not administrator, so he can only manage course in his department
            // or his responsible department
            if ($request->get('department') != null && $request->get('department') != "") {
                $department_id = $request->get('department');

                $course_annuals = $course_annuals->where(function ($query) use ($department_id) {

                    $employee = Employee::where('user_id', Auth::user()->id)->first();
                    if ($department_id != $employee->department->id) {
                        // in different department
                        $query->where('department_id', $department_id)->where('responsible_department_id', $employee->department->id);
                    } else {
                        // in same department
                        $query->where('department_id', $department_id);
                    }
                });
            }
        }

        if ($request->get('degree') != null && $request->get('degree') != "") {
            $course_annuals = $course_annuals->where('degree_id', $request->get('degree'));
        }
        if ($request->get('grade') != null && $request->get('grade') != "") {
            $course_annuals = $course_annuals->where('grade_id', $request->get('grade'));
        }
        if ($request->get('semester') != null && $request->get('semester') != "") {
            $course_annuals = $course_annuals->where('semester_id', $request->get('semester'));
        }
        if ($request->get('lecturer') != null && $request->get('lecturer') != "") {
            $course_annuals = $course_annuals->where('employee_id', $request->get('lecturer'));
        }
        if ($request->get('dept_option') != null && $request->get('dept_option') != "") {
            $course_annuals = $course_annuals->where('department_option_id', $request->get('dept_option'));
        }

        $datas = $course_annuals->get();
        $result = true;


        foreach ($datas as $data) {
            $data->is_allow_scoring = $status;

            if (!$data->save()) {
                return false;
            }
        }

        return $result;
    }

    public function disable_scoring(ToggleScoringCourseAnnualRequest $request)
    {
        if ($request->ajax()) { // Only accept through ajax
            if ($this->mass_toggle_scoring($request, "no")) {
                return \Illuminate\Support\Facades\Response::json(array("success" => true, "message" => "All given courses are blocked from scoring."));
            } else {
                return \Illuminate\Support\Facades\Response::json(array("success" => false, "message" => "Something went wrong."));
            }
        }
    }

    public function only_retake_scoring(ToggleScoringCourseAnnualRequest $request)
    {
        if ($request->ajax()) { // Only accept through ajax
            if ($this->mass_toggle_scoring($request, "only_retake")) {
                return \Illuminate\Support\Facades\Response::json(array("success" => true, "message" => "All given courses are blocked from scoring."));
            } else {
                return \Illuminate\Support\Facades\Response::json(array("success" => false, "message" => "Something went wrong."));
            }
        }
    }

    public function saveEachObservation(Request $request)
    {


        //----update observation by student id_card

        $student = DB::table('students')
            ->where('id_card', $request->student_id_card)
            ->update(['observation' => $request->observation]);
        if ($student) {

            $this->courseAnnualScores->getUserLog($student, 'Student', 'Update-Observation');
            return Response::json(['status' => true]);
        }
    }

    public function saveEachRemark(Request $request)
    {
        //----update remark by student id_card in table student_annuals

        if (Auth::user()->allow('write-student-remark')) {
            $student = DB::table('students')->where('id_card', $request->student_id_card)->first();

            $student_annual = DB::table('studentAnnuals')
                ->where('student_id', $student->id)
                ->where('academic_year_id', $request->academic_year_id)
                ->update(['remark' => $request->remark]);
            if ($student_annual) {
                $this->courseAnnualScores->getUserLog($student_annual, 'StudentAnnual', 'Update-Remak');
                return Response::json(['status' => true]);
            }
        } else {
            return Response::json(['status' => false, 'message' => 'Forbidden!']);
        }

    }

    public function saveEachGeneralRemark(Request $request)
    {
        //----update remark by student id_card in table student_annuals
        $student = DB::table('students')->where('id_card', $request->student_id_card)->first();

        $student_annual = DB::table('studentAnnuals')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $request->academic_year_id)
            ->update(['general_remark' => $request->general_remark]);
        if ($student_annual) {

            $this->courseAnnualScores->getUserLog($student_annual, 'StudentAnnual', 'Update-General-Remark');
            return Response::json(['status' => true]);
        }
    }

    public function exportTotalScore(Request $request)
    {

        $array_data = $this->allHandsontableData($request);

        $array_data = json_decode($array_data);
        $array_data = json_encode($array_data);
        $array_data = json_decode($array_data, true);
        $tableData = $this->assignValueRattrapage($array_data['data'], $array_data['array_fail_subject']);
        $array_data['data'] = $tableData;

        $academicYear = DB::table('academicYears')->where('id', $request->academic_year_id)->first();
        $department = DB::table('departments')->where('id', $request->department_id)->first();
        $degree = Degree::find($request->degree_id)->first();
        $grade = $request->grade_id;
        $first_headers = [];
        $second_headers = [];
        $col_span = [];
        $letter = 'A';
        $alpha = [];

        // -----first headers
        foreach ($array_data['nestedHeaders'][0] as $header) {

            if (is_array($header)) {

                // ---arrang column-span
                $col = $letter . '6:';
                $first_headers[] = $header['label'];
                for ($i = 1; $i < $header['colspan']; $i++) {
                    $letter++;
                    $alpha[] = $letter;

                    $first_headers[] = "";

                }
                $col_span[] = $col . $letter . '6';

            } else {

                if ($header == '') {
                    $first_headers[] = 'No';
                } else {
                    $first_headers[] = $header;
                }
            }

            $alpha[] = $letter;
            $letter++;
            $alpha = array_unique($alpha);
        }

        //-----second headers

        foreach ($array_data['nestedHeaders'][1] as $second_header) {

            if (is_array($second_header)) {
                if ($second_header['label'] != 'remark' && $second_header['label'] != 'redouble' && $second_header['label'] != 'rattrapage' && $second_header['label'] != 'rank' && $second_header['label'] != 'observation') {
                    $second_headers[] = $second_header['label'];
                } else {
                    $second_headers[] = '';
                }
            } else {
                if ($second_header != 'remark' && $second_header != 'redouble' && $second_header != 'rattrapage' && $second_header != 'rank' && $second_header != 'observation') {
                    $second_headers[] = $second_header;
                } else {
                    $second_headers[] = '';
                }
            }
        }

        Excel::create('Student Final Result', function ($excel) use ($grade, $degree, $department, $academicYear, $array_data, $alpha, $first_headers, $second_headers, $col_span) {


            $excel->sheet('Student List Score', function ($sheet) use ($grade, $degree, $department, $academicYear, $array_data, $alpha, $first_headers, $second_headers, $col_span) {

                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->setAllBorders('thin');
                // Font size
                $sheet->setFontSize(10);

                //---- create header and subheader--------
                $tmp_alpha = [];
                $index = 0;
                $header_data = [];
                $sub_header_data = [];
                $school_name = [];
                $school_name = array_merge($school_name, ['']);
                $school_name[] = 'Institut de Technologie du Cambodge';
                $sub_sub_header_data = [];
                $sub_sub_header_data = array_merge($sub_sub_header_data, ['']);
                $sub_sub_header_data[] = 'Département:' . $department->name_fr;
                $class = [];
                $class = array_merge($class, ['']);
                $class[] = 'Classe:' . $degree->code . $grade . '-' . $department->code;


                //---end herer------

                //----alpha array of columns (A-Z--ZZ)
                foreach ($alpha as $l) {

                    //---set width for specific column
                    if ($l == 'C') {
                        $sheet->setWidth([$l => 20]);
                    } elseif ($l == 'A') {
                        $sheet->setWidth([$l => 5]);
                    } elseif ($l == 'B') {
                        $sheet->setWidth([$l => 15]);
                    } else {
//
                        $sheet->setSize($l . '6', 10, 150);
                    }

                    //----assigne cell value in the middle of sheet

                    if ($index == (ceil(count($alpha) / 2))) {
                        $header_data[] = 'RELEVE DES NOTES DE CONTROLE';
                        $sub_header_data[] = 'Année Scolaire ' . $academicYear->name_latin;

                    } else {

                        //----adding empty space
                        $header_data[] = '';
                        $sub_header_data[] = '';
                        $sub_sub_header_data[] = '';
                    }
                    $index++;
                    $tmp_alpha[] = $l;// store array alpha by order of array index


                }


                /*---set styling cell property ----*/
                //dd($tmp_alpha[0].'1:'.$tmp_alpha[count($tmp_alpha)-1].'1')---(A1:..Z:1) set col-A row-1 to col-Z row-1

                $sheet->cells($tmp_alpha[0] . '1:' . $tmp_alpha[count($tmp_alpha) - 1] . '1', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '18'
                    ));
                });


                $sheet->cells($tmp_alpha[0] . '2:' . $tmp_alpha[count($tmp_alpha) - 1] . '2', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '14'
                    ));
                });

                $sheet->cells($tmp_alpha[0] . '3:' . $tmp_alpha[count($tmp_alpha) - 1] . '3', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '14'
                    ));
                });

                $sheet->cells($tmp_alpha[0] . '4:' . $tmp_alpha[count($tmp_alpha) - 1] . '4', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '14'
                    ));
                });

                $sheet->cells($tmp_alpha[0] . '5:' . $tmp_alpha[count($tmp_alpha) - 1] . '5', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '14'
                    ));
                });


                $sheet->cells($tmp_alpha[0] . '6:' . $tmp_alpha[count($tmp_alpha) - 1] . '6', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setTextRotation(-90);
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells($tmp_alpha[0] . '7:' . $tmp_alpha[count($tmp_alpha) - 1] . '7', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                /*--merge colum---*/
                foreach ($col_span as $span) {
                    $sheet->mergeCells($span);
                }

                /*--set row sheet value--*/
                $sheet->row(1, $header_data);
                $sheet->row(2, $sub_header_data);
                $sheet->row(3, $school_name);
                $sheet->row(4, $sub_sub_header_data);
                $sheet->row(5, $class);
                $sheet->row(6, $first_headers);
                $sheet->row(7, $second_headers);
                foreach ($array_data['data'] as $data) {
                    $row = [];
                    foreach ($data as $d) {
                        $row = array_merge($row, [$d]);
                    }

                    $sheet->appendRow($row);

                }
            });

        })->export('xls');
    }

    /**
     * @param $arrayData
     * @param $arrayFailSubject
     * @return array
     */
    private function assignValueRattrapage($arrayData, $arrayFailSubject)
    {
        $dataWithRattrapage = [];

        foreach ($arrayData as $data) {

            if ($data['student_id_card'] != null) {

                if ($data['Moyenne'] >= ScoreEnum::Pass_Moyenne) {

                    $arrayPassOrFails = $arrayFailSubject[$data['student_id_card']]; // array of every subjects of student with score
                    if (isset($arrayPassOrFails['fail'])) {
                        $data['Rattrapage'] = count($arrayPassOrFails['fail']);
                    } else {
                        $data['Rattrapage'] = ScoreEnum::Zero;
                    }
                } else {

                    $numberRattrapage = $this->numberRattrapage($arrayFailSubject[$data['student_id_card']]);
                    $data['Rattrapage'] = $numberRattrapage;
                }
            }

            $dataWithRattrapage[] = $data;
        }
        return $dataWithRattrapage;

    }

    private function numberRattrapage($arrayFailSubject)
    {

        $subjectRattrapages = $this->findRattrapageSubject($arrayFailSubject);

        if (isset($subjectRattrapages['fail'])) {
            return count($subjectRattrapages['fail']);
        } else {
            return ScoreEnum::Zero;
        }
    }

    public function isAllowScoring(Request $request)
    {

        $courseAnnual = $this->courseAnnuals->findOrThrowException($request->course_annual_id);
        if ($courseAnnual->is_allow_scoring == "yes") {
            return Response::json(['status' => true, 'message' => 'Allowed all scoring!']);
        } else if ($courseAnnual->is_allow_scoring == "only_retake") {
            return Response::json(['status' => true, 'message' => 'Allowed scoring only retake exam!']);
        } else {
            return Response::json(['status' => false, 'message' => 'You are not allowed to make any changes on the score sheet, please ask the administrator to enable scoring!!']);
        }
    }

    public function studentRedoubleListe(Request $request)
    {

        $semesterId = $request->semester_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $academicYearId = $request->academic_year_id;
        $departmentOptionId = $request->dept_option_id;
        $departmentId = $request->department_id;
        $academicYear = AcademicYear::where('id', $academicYearId)->first();

        $studentDataProperties = $this->studentResitData($request);
        $status = true;

        if (!$studentDataProperties['status']) {

            $status = $studentDataProperties['status'];

            return view('backend.course.courseAnnual.includes.student_redouble_lists',
                ['status' => false,
                    'message' => $studentDataProperties['message'],
                    'academicYear' => $academicYear,
                    'semesterId' => $semesterId,
                    'degreeId' => $degreeId,
                    'gradeId' => $gradeId,
                    'departmentOptionId' => $departmentOptionId,
                    'departmentId' => $departmentId,
                    'academicYearId' => $academicYearId
                ]);
        }

        $studentRattrapages = $studentDataProperties['student_rattrapage'];
        $failCourseAnnuals = $studentDataProperties['all_fail_subject'];
        $fullUrl = $studentDataProperties['full_url'];
        $courseAnnuals = $studentDataProperties['course_annual'];


        return view('backend.course.courseAnnual.includes.student_redouble_lists',
            compact(
                'status',
                'courseAnnuals',
                'studentRattrapages',
                'academicYear',
                'fullUrl',
                'failCourseAnnuals',
                'semesterId', 'degreeId', 'gradeId', 'departmentOptionId', 'departmentId', 'academicYearId')
        );
    }

    public function resitSubjectLists(Request $request)
    {
//        $studentDataProperties = $this->studentResitData($request);

        $studentDataProperties = $this->studentResitData($request);

        $students = $studentDataProperties['student'];
        $coursePrograms = $studentDataProperties['courseprogram'];
        $studentRattrapages = $studentDataProperties['student_rattrapage'];
        $courseAnnualByProgram = $studentDataProperties['course_annual_by_program'];
        $academicYear = $studentDataProperties['academic_year'];
        $averages = $studentDataProperties['average'];
        $onlyResitCourseAnnuals = $studentDataProperties['resit_course_annual'];
        $fullUrl = $studentDataProperties['full_url'];

        return view('backend.course.courseAnnual.includes.resit_subject_lists',
            compact(
                'students',
                'coursePrograms',
                'courseAnnualByProgram',
                'academicYear', 'averages',
                'studentRattrapages', 'onlyResitCourseAnnuals',
                'fullUrl'
            )
        );

    }

    public function exportStudentRedoubleList(Request $request)
    {

        $data = $request->all();

        $courseAnnualByPrograms = [];
        $academic_year_id = $request->academic_year_id;
        $academicYear = DB::table('academicYears')->where('id', $academic_year_id)->first();
        $supplementary_subjects = [];
        $data_lists = [];
        $mustRattrapageStudents = [];
        foreach ($data as $studentIdCard => $failCourses) {

            if (is_array($failCourses)) {
                foreach ($failCourses as $course_annual_id) {

                    if (!in_array($course_annual_id, $supplementary_subjects)) {
                        $supplementary_subjects[] = $course_annual_id;
                    }
                }
                $mustRattrapageStudents[] = $studentIdCard;
            }

        }

        $course_program_ids = [];

        $courseAnnuals = DB::table('course_annuals')->whereIn('id', $supplementary_subjects)->get();


        foreach ($courseAnnuals as $courseAnnual) {

            $courseAnnualByPrograms[$courseAnnual->course_id][] = $courseAnnual->id;
            if (!in_array($courseAnnual->course_id, $course_program_ids)) {
                $course_program_ids[] = $courseAnnual->course_id;
            }
        }

        $coursePrograms = Course::whereIn('id', $course_program_ids)->get();
        $students = DB::table('students')
            ->join('studentAnnuals', function ($query) use ($academic_year_id) {
                $query->on('studentAnnuals.student_id', '=', 'students.id')
                    ->where('academic_year_id', '=', $academic_year_id);
            })
            ->join('genders', 'genders.id', '=', 'students.gender_id')
            ->whereIn('id_card', $mustRattrapageStudents)
            ->orderBy('name_latin')
            ->select(
                'students.name_latin', 'students.id as student_id',
                'genders.code', 'students.id_card', 'studentAnnuals.id as student_annual_id'
            );
        $studentAnnualIds = $students->lists('student_annual_id');
        $students = $students->get();

        $studentScores = DB::table('averages')
            ->whereIn('student_annual_id', $studentAnnualIds)
            ->get();

        $objectScores = [];

        $objectScoreCollection = collect($studentScores);

        $allCourseAnnualIds = $objectScoreCollection->unique('course_annual_id')->pluck('course_annual_id')->toArray();
        $allCourseAnnuals = DB::table('course_annuals')->whereIn('id', $allCourseAnnualIds)->get();
        $allCredits = collect($allCourseAnnuals)->sum('credit');
        $allCourseAnnuals = collect($allCourseAnnuals)->keyBy('id')->toArray();


        $objectScoreCollection->filter(function ($item) use (&$objectScores) {

            $objectScores[$item->student_annual_id][$item->course_annual_id] = $item;
        });

        $index = 1;
        $true = true;
        $row_header = [];

        foreach ($students as $student) {

            if ($true) {
                $one_student = $student;
                $true = false;
            }

            $array = [$index, $student->id_card, $student->name_latin, $student->code];
            $row_header = ['No', 'Student ID', 'Student Name', 'M/F'];

            foreach ($coursePrograms as $courseProgram) {

                if ($courseProgram->is_counted_creditability) {

                    $row_header = array_merge($row_header, [$courseProgram->name_en]);

                    $intersetedCourses = array_intersect($courseAnnualByPrograms[$courseProgram->id], $data[$student->id_card]);
                    if (count($intersetedCourses) > 0) {

                        if (count($intersetedCourses) == 1) {

                            $intersetedCourses = array_values($intersetedCourses);
                            $studentScoreCourses = $objectScores[$student->student_annual_id];
                            $eachScore = isset($studentScoreCourses[$intersetedCourses[0]]) ? $studentScoreCourses[$intersetedCourses[0]] : null;
                            $array = array_merge($array, [$eachScore->average]);
                        }

                        /*---here if the count($intersectedCourse) > 1 that mean there is an error that one student have assigned two course-annuals that have the same course program--- */

                    } else {

                        $array = array_merge($array, ['']);
                    }
                }
            }

            $eachStudentScore = $objectScores[$student->student_annual_id];


            $sumScores = collect($eachStudentScore)->sum(function ($item) use ($allCourseAnnuals, $allCredits, &$sum_scores) {
                $martchedCourse = $allCourseAnnuals[$item->course_annual_id];

                if ($martchedCourse->is_counted_creditability) {
                    return $item->average * $martchedCourse->credit;
                }
            });

            $row_header = array_merge($row_header, ['Moyenne']);
            $array = array_merge($array, [$this->floatFormat($sumScores / $allCredits)]);
            $count = count($array);
            $data_lists[] = $array;
            $index++;
        }

        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }

        $studentAnnual = DB::table('studentAnnuals')->where([
            ['student_id', $one_student->student_id],
            ['academic_year_id', $academic_year_id]
        ])->first();

        $department = DB::table('departments')->where('id', $studentAnnual->department_id)->first();
        $degree = DB::table('degrees')->where('id', $studentAnnual->degree_id)->first();
        $grade = DB::table('grades')->where('id', $studentAnnual->grade_id)->first();
        $header = 'Student Supplementary Exam Lists';
        $sub_header = 'Academic Year: ' . $academicYear->name_latin;
        $schoolTitle = 'Institute of Technology of Cambodia';
        $class = $degree->code . $grade->code . '-' . $department->code;


        Excel::create('Student Supplementary Courses', function ($excel) use ($data_lists, $alpha, $count, $header, $sub_header, $schoolTitle, $class, $row_header, $department) {

            $excel->sheet('Student Supplementary Courses', function ($sheet) use ($data_lists, $alpha, $count, $header, $sub_header, $schoolTitle, $class, $row_header, $department) {


                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');

                $sheet->cells('A1:' . $alpha[$count - 1] . '1', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '16'
                    ));
                });

                $sheet->cells('A2:' . $alpha[$count - 1] . '2', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A3:' . $alpha[$count - 1] . '3', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });
                $sheet->cells('A4:' . $alpha[$count - 1] . '4', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A5:' . $alpha[$count - 1] . '5', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A6:' . $alpha[$count - 1] . '6', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('left');
                    $cells->setValignment('middle');
                    $cells->setTextRotation(-90);
                    $cells->setFont(array(
                        'size' => '12',
                        'font' => 'bold'

                    ));
                });
                for ($key = 0; $key < $count; $key++) {
                    if ($alpha[$key] == 'C') {
                        $sheet->setWidth([$alpha[$key] => 15]);
                    } else {
                        $sheet->setSize($alpha[$key] . '6', 10, 150);
                    }
                }
                $sheet->row(1, []);
                $sheet->row(2, []);
                $sheet->row(3, [$schoolTitle]);
                $sheet->row(4, ['Department: ' . $department->name_en]);
                $sheet->row(5, [$class]);
                $sheet->row(6, $row_header);
                $sheet->setCellValue($alpha[$count / 2] . '1', $header);
                $sheet->setCellValue($alpha[$count / 2] . '2', $sub_header);

                foreach ($data_lists as $data) {
                    $sheet->appendRow($data);
                }
            });

        })->download('xls');


    }

    /**
     * @param Request $request
     */
    public function exportSupplementarySubjects(Request $request)
    {

        $academicYearId = $request->academic_year_id;
        $studentAnnualIds = $request->student_annual_id;
        $courseAnnualIds = $request->course_annual_id;
        $studentFailByCourses = $request->course;
        $rooms = $request->room;
        $dateStartEnd = $request->date_start_end;

        foreach ($dateStartEnd as $date) {
            if ($date == null || $date == '') {
                return redirect()->back()->with(['error' => 'Please complete all Date Time Field!']);
            }
        }
        foreach ($rooms as $room) {
            if ($room == null || $room == '') {
                return redirect()->back()->with(['error' => 'Please complete all Room Field!']);
            }
        }

        $courseAnnuals = CourseAnnual::whereIn('id', $courseAnnualIds)->get();
        $courseAnnuals = collect($courseAnnuals)->keyBy('id')->toArray();
        $studentAnnuals = StudentAnnual::join('students', function ($query) use ($studentAnnualIds) {
            $query->on('studentAnnuals.student_id', '=', 'students.id')
                ->whereIn('studentAnnuals.id', $studentAnnualIds);
        })->select('studentAnnuals.*', 'students.name_latin', 'students.name_kh', 'students.id_card')
            ->get();

        $studentAnnuals = collect($studentAnnuals)->keyBy('id')->toArray();

        $tableHeader = [
            'No',
            'Subjects',
            'student',
            'Date Time Start',
            'Date Time End',
            'Room'
        ];

        $academicYear = DB::table('academicYears')->where('id', $academicYearId)->first();
        $department = DB::table('departments')->where('id', $request->department_id)->first();
        $degree = DB::table('degrees')->where('id', $request->degree_id)->first();
        $grade = DB::table('grades')->where('id', $request->grade_id)->first();
        if ($request->department_option_id != null && $request->department_option_id != '') {

            $departmentOption = DB::table('departmentOptions')->where('id', $request->department_option_id)->first();
        } else {

            $departmentOption = null;
        }

        $header = 'Student Supplementary Exam Lists';
        $sub_header = 'Academic Year: ' . $academicYear->name_latin;
        $schoolTitle = 'Institute of Technology of Cambodia';
        $class = $degree->code . $grade->code . '-' . $department->code;

        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }

        Excel::create('Supplementary Course Schedule', function ($excel) use ($courseAnnuals, $studentAnnuals, $studentFailByCourses, $rooms, $dateStartEnd, $alpha, $header, $sub_header, $schoolTitle, $class, $tableHeader, $department) {

            $excel->sheet('Supplementary Course Schedule', function ($sheet) use ($courseAnnuals, $studentAnnuals, $studentFailByCourses, $rooms, $dateStartEnd, $alpha, $header, $sub_header, $schoolTitle, $class, $tableHeader, $department) {

                $count = count($tableHeader);
                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left

                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');

                $sheet->cells('A1:' . $alpha[$count - 1] . '1', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '16'
                    ));
                });

                $sheet->cells('A2:' . $alpha[$count - 1] . '2', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A3:' . $alpha[$count - 1] . '3', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });
                $sheet->cells('A4:' . $alpha[$count - 1] . '4', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A5:' . $alpha[$count - 1] . '5', function ($cells) {
                    $cells->setBackground('#E6F4F8 ');
                    $cells->setAlignment('left');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });

                $sheet->cells('A6:' . $alpha[$count - 1] . '6', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    //$cells->setTextRotation(-90);
                    $cells->setFont(array(
                        'size' => '12',
                        'font' => 'bold'

                    ));
                });


                for ($key = 0; $key < $count; $key++) {

                    $sheet->setWidth([$alpha[$key] => 20]);
                }

                $sheet->setWidth(array(
                    'B' => 30,
                    'C' => 25
                ));


                $sheet->row(1, []);
                $sheet->row(2, []);
                $sheet->row(3, [$schoolTitle]);
                $sheet->row(4, ['Department: ' . $department->name_en]);
                $sheet->row(5, [$class]);
                $sheet->row(6, $tableHeader);

                $sheet->setCellValue($alpha[$count / 2] . '1', $header);
                $sheet->setCellValue($alpha[$count / 2] . '2', $sub_header);

                $index = 0;
                $countMerge = 0;
                $i = 7;
                foreach ($studentFailByCourses as $course_annual_id => $student_annual_ids) {


                    $date = explode('-', $dateStartEnd[$course_annual_id]);

                    $start = DateTimeManager::fullDateWithTime(trim(' ', $date[0]));

                    $end = DateTimeManager::fullDateWithTime(trim(' ', $date[1]));

                    $courseAnnual = $courseAnnuals[$course_annual_id];

                    $index++;
                    $row = ['No' => $index, 'Subject' => $courseAnnual['name_en'], 'Student' => '', 'Date-time-start' => $start, 'Date-time-end' => $end, 'Room' => $rooms[$course_annual_id]];
                    foreach ($student_annual_ids as $id) {

                        $student = $studentAnnuals[$id];
                        $row['Student'] = $student['name_latin'];
                        $sheet->row($i++, $row);
                        $sheet->setHeight([$i - 1 => 20]);
                    }

                    if (count($student_annual_ids) > 1) {


                        $sheet->mergeCells('A' . (6 + ($countMerge + 1)) . ':' . 'A' . ((6 + ($countMerge)) + count($student_annual_ids)))->setCellValue('A' . (6 + ($countMerge + 1)), $index);
                        $sheet->mergeCells('B' . (6 + ($countMerge + 1)) . ':' . 'B' . ((6 + ($countMerge)) + count($student_annual_ids)));
                        $sheet->mergeCells('D' . (6 + ($countMerge + 1)) . ':' . 'D' . ((6 + ($countMerge)) + count($student_annual_ids)));
                        $sheet->mergeCells('E' . (6 + ($countMerge + 1)) . ':' . 'E' . ((6 + ($countMerge)) + count($student_annual_ids)));
                        $sheet->mergeCells('F' . (6 + ($countMerge + 1)) . ':' . 'F' . ((6 + ($countMerge)) + count($student_annual_ids)));


                        $sheet->cells('A' . (6 + ($countMerge + 1)) . ':' . 'A' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });


                        $sheet->cells('B' . (6 + ($countMerge + 1)) . ':' . 'B' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->cells('D' . (6 + ($countMerge + 1)) . ':' . 'D' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->cells('E' . (6 + ($countMerge + 1)) . ':' . 'E' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->cells('F' . (6 + ($countMerge + 1)) . ':' . 'F' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $countMerge += count($student_annual_ids);
                    } else {


                        $sheet->cells('A' . (6 + ($countMerge + 1)) . ':' . 'A' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setBorder(array(
                                'top', 'right', 'bottom', 'left'
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->cells('B' . (6 + ($countMerge + 1)) . ':' . 'B' . ((6 + ($countMerge)) + count($student_annual_ids)), function ($cells) {

                            $cells->setFont(array(
                                'name' => 'Times New Roman',
                                'size' => 12,
                                'bold' => true
                            ));
                            $cells->setFontColor("#4d4d4d");
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        $countMerge += count($student_annual_ids);
                    }
                }
            });

        })->download('xls');
    }

    public function getStudentFinalResult(Request $request)
    {
        // This function is in the App/traits folder
        return $this->getStudentScoreBySemester(20486, 1);

    }

    public function saveStudentResit(Request $request)
    {

        $arrayCourseAnnualIds = [];
        $academicYearId = $request->academic_year_id;
        $studentIdCards = $request->student_id_card;
        $data = $request->all();
        foreach ($studentIdCards as $idCard) {
            if (isset($data[$idCard])) {
                $arrayCourseAnnualIds = array_merge($arrayCourseAnnualIds, $data[$idCard]);
            }

        }
        $arrayCourseAnnualIds = array_unique($arrayCourseAnnualIds);
        $arrayCourseAnnualIds = array_values($arrayCourseAnnualIds);

        $semesterIds = DB::table('course_annuals')->whereIn('id', $arrayCourseAnnualIds)->lists('semester_id', 'id');

        $students = $this->getStudentByIdCardYearly($studentIdCards, $academicYearId);


        foreach ($students as $student) {

            $studentResitSocre = $this->getStudentResitScore($student->student_annual_id);

            if ($resitCourses = $studentResitSocre->get()) {

                $destroy = $studentResitSocre->delete();
                if ($destroy) {

                    if (isset($data[$student->id_card])) {

                        $courseAnnualIds = $data[$student->id_card];
                        foreach ($courseAnnualIds as $courseAnnualId) {

                            $input = [
                                'student_annual_id' => $student->student_annual_id,
                                'course_annual_id' => $courseAnnualId,
                                'semester_id' => $semesterIds[$courseAnnualId]
                            ];

                            $this->resitStudentAannuals->create($input);
                        }

                    } else {
                        //---store one student back

                        if ($resitCourses) {

                            $true = true;
                            foreach ($resitCourses as $course) {
                                if ($true) {
                                    $input = [
                                        'student_annual_id' => $course->student_annual_id,
                                        'course_annual_id' => null,
                                        'semester_id' => $course->semester_id
                                    ];
                                    $this->resitStudentAannuals->create($input);
                                    $true = false;
                                } else {
                                    break;
                                }
                            }

                        }

                    }
                }
            } else {

                if (isset($data[$student->id_card])) {

                    $courseAnnualIds = $data[$student->id_card];

                    foreach ($courseAnnualIds as $courseAnnualId) {

                        $input = [
                            'student_annual_id' => $student->student_annual_id,
                            'course_annual_id' => $courseAnnualId,
                            'semester_id' => $semesterIds[$courseAnnualId]
                        ];

                        $this->resitStudentAannuals->create($input);
                    }
                } else {

                    $input = [
                        'student_annual_id' => $student->student_annual_id,
                        'course_annual_id' => null,
                        'semester_id' => $semesterIds[$courseAnnualId]
                    ];

                    $this->resitStudentAannuals->create($input);
                }
            }
        }
        return Response::json(['status' => true, 'message' => 'Changes Saved!']);
    }

    public function updateStudentStatus(Request $request)
    {

        if (auth()->user()->allow("evaluate-student")) {

            $academicYearId = $request->academic_year_id;
            $studentIdCard = $request->student_id_card;
            $redouble = $request->redouble;

            $student = Student::where('id_card', $studentIdCard)->select('students.id as student_id', 'students.radie')->first();

            $studentAnnual = StudentAnnual::where([
                ['student_id', $student->student_id],
                ['academic_year_id', $academicYearId]
            ])->first();

            $degree = $studentAnnual->degree;
            $grade = $studentAnnual->grade;

            if ($degree->code == ScoreEnum::ENGINEER) {
                $redName = ScoreEnum::Red_I . $grade->code;
            } else {
                $redName = ScoreEnum::Red_T . $grade->code;
            }

            if ($redouble == ScoreEnum::RADIE) {
                $update = $this->updateStatusStudent($studentIdCard, $status = true);
                if ($update) {
                    return Response::json(['status' => true, 'message' => 'updated']);
                }
            } else if ($redouble == 'P') {


                /*---set student redouble to pass in this year ---*/

                $redouble_students = DB::table('redouble_student')
                    ->where([
                        ['student_id', $student->student_id],
                        ['academic_year_id', $academicYearId]
                    ]);

                if (count($redouble_students->get()) > 0) {

                    $this->courseAnnualScores->getUserLog($redouble_students->get(), 'Redouble', 'Delete');
                    $delete = $redouble_students->delete();
                    if ($delete) {

                        /*--check if student has set to be radie ----then update not to be radie --*/

                        if ($student->radie) {
                            $update = $this->updateStatusStudent($studentIdCard, $status = false);
                        }
                        return Response::json(['status' => true, 'message' => 'changed!']);
                    }
                } else {


                    if ($student->radie) {
                        $update = $this->updateStatusStudent($studentIdCard, $status = false);
                    }

                    return Response::json(['status' => true, 'message' => 'This student is not fail in this year!']);
                }


            } else {
                /*---student Redouble not Radie ---*/
                $update = $this->updateStatusStudent($studentIdCard, $status = false);

                /*---check if current year has redouble record ---*/
                $redouble_students = DB::table('redouble_student')
                    ->where([
                        ['student_id', $student->student_id],
                        ['academic_year_id', $academicYearId]
                    ]);

                if (count($redouble_students->get()) > 0) {

                    $this->courseAnnualScores->getUserLog($redouble_students->get(), 'Redouble-Student', 'Update');
                    $redouble_students->update(['is_changed' => true]);


                    return Response::json(['status' => true, 'message' => 'changed!']);
                } else {

                    $create = $this->createRedoubleRecord($student, $redName, $academicYearId, $isChanged = true);
                    return Response::json(['status' => true, 'message' => 'changed!']);
                }
            }
        } else {
            return Response::json(['status' => false, 'message' => 'Permission Denied!']);
        }


        /*else if($redouble == ScoreEnum::Pass) {

            $update = $this->updateStatusStudent($studentIdCard, $status= false);
            if($update) {
                $redouble_students = DB::table('redouble_student')->where('student_id', $student->id);

                if(count($redouble_students->get()) > 0) {

                    $updateRedouble = $redouble_students->where('academic_year_id', $academicYearId);

                    if($updateRedouble->get()) {

                        $updateRedouble->update(['is_changed' => true]);

                        return Response::json(['status' => true, 'message' => 'Updated']);
                    } else {
                        return Response::json(['status' => true, 'message' => 'Updated']);
                    }


                } else {
                    return Response::json(['status' => true, 'message' => 'Updated']);
                }
            }
        } else {

            /*---change from radie to Red---*/


        /*$previousValue = $request->old_value;
        if($previousValue == ScoreEnum::RADIE) {
            return Response::json(['status' => false, 'message' => 'You cannot make change!']);
        } else {

            $redouble_students = DB::table('redouble_student')
                ->where([
                    ['student_id', $student->id],
                    ['academic_year_id', $academicYearId]
                ]);
            if(count($redouble_students->get()) > 0 ) {
                $redouble_students->update(['is_changed' => false]);

                return Response::json(['status' => true, 'message' => 'changed!']);
            } else {

                $this->createRedoubleRecord($student, $redName, $academicYearId );
                return Response::json(['status' => true, 'message' => 'changed!']);
            }
        }
    }*/

    }

    public function getStudentDismiss(Request $request)
    {

        $studentRadies = [];
        $studentData = [];
        $index = 1;

        $department = Department::where('id', $request->department_id)->first();
        $students = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            ->where([
                ['department_id', $request->department_id],
                ['studentAnnuals.academic_year_id', $request->academic_year_id]
            ])
            ->orderBy('studentAnnuals.degree_id')
            ->orderBy('studentAnnuals.grade_id')
            ->orderBy('studentAnnuals.department_option_id')
            ->get();


        foreach ($students as $student) {

            if ($student->radie == true) {

                $gender = Gender::where('id', $student->gender_id)->first();
                $grade = Grade::where('id', $student->grade_id)->first();

                if ($student->department_option_id) {
                    $departmentOption = DepartmentOption::where('id', $student->department_option_id)->first();
                    $element = [
                        $index,
                        $student->id_card,
                        $student->name_latin,
                        $gender->name_en,
                        $grade->name_en,
                        $departmentOption->name_en
                    ];

                    $headers = [
                        'No',
                        'ID Card',
                        'Name Latin',
                        'Sexe',
                        'Grade',
                        'Option'
                    ];
                } else {

                    $element = [
                        $index,
                        $student->id_card,
                        $student->name_latin,
                        $gender->name_en,
                        $grade->name_en,
                    ];

                    $headers = [
                        'No',
                        'ID Card',
                        'Name Latin',
                        'Sexe',
                        'Grade',
                    ];
                }

                $studentData[] = $element;
                $index++;

            } else {

                $headers = [
                    'No',
                    'ID Card',
                    'Name Latin',
                    'Sexe',
                    'Grade',
                ];

            }
        }
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        $title = 'Student Elimination Lists';

        Excel::create('Supplementary Course Schedule', function ($excel) use ($alpha, $title, $headers, $studentData, $studentRadies, $department) {

            $excel->sheet('Supplementary Course Schedule', function ($sheet) use ($alpha, $title, $headers, $studentData, $studentRadies, $department) {

                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');
                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');

                $sheet->cells('A1:F1', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '16'
                    ));
                });
                $sheet->cells('A2:F2', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });
                $sheet->cells('A3:F3', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    //$cells->setTextRotation(-90);
                    $cells->setFont(array(
                        'size' => '12',
                        'font' => 'bold'

                    ));
                });
                $sheet->row(2, ['', 'Department: ' . $department->name_en]);
                $sheet->row(3, $headers);
                $sheet->setCellValue('C1', $title);

                for ($key = 0; $key < 6; $key++) {
                    if ($alpha[$key] == 'C') {
                        $sheet->setWidth([$alpha[$key] => 15]);
                    } else {
                        $sheet->setSize($alpha[$key] . '3', 10, 20);
                    }
                }

                foreach ($studentData as $data_row) {
                    $sheet->appendRow($data_row);

                }
            });

        })->download('xls');

    }

    public function getStudentRedouble(Request $request)
    {

        $deptId = $request->department_id;
        $academicYearID = $request->academic_year_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $semesterId = $request->semester_id;
        $deptOptionId = $request->dept_option_id;

        $department = Department::where('id', $deptId)->first();
        $academicYear = AcademicYear::where('id', $academicYearID)->first();
        $degree = Degree::where('id', $degreeId)->first();
        $grade = Grade::where('id', $gradeId)->first();

        $studentAnnuals = $this->getStudentByDeptIdGradeIdDegreeId([$deptId], [$degreeId], [$gradeId], $academicYearID);
        if ($deptOptionId = $request->dept_option_id) {

            $option = DepartmentOption::where('id', $deptOptionId)->first();
            $studentIds = $studentAnnuals->whereIn('studentAnnuals.department_option_id', [$deptOptionId])->lists('student_id');
            $studentAnnuals = $studentAnnuals->whereIn('studentAnnuals.department_option_id', [$deptOptionId])
                ->orderBy('students.name_latin')
                ->get();
        } else {
            $option = null;
            $studentIds = $studentAnnuals->lists('student_id');
            $studentAnnuals = $studentAnnuals
                ->orderBy('students.name_latin')
                ->get();
        }

        $studentRedoubles = $this->studentRedoubleFromDB($studentIds, $request->academic_year_id);


        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        $title = 'Student Repetition Lists';

        Excel::create('Supplementary Course Schedule', function ($excel) use ($alpha, $title, $studentRedoubles, $studentAnnuals, $department, $academicYear, $degree, $grade, $option) {

            $excel->sheet('Student Redouble', function ($sheet) use ($alpha, $title, $studentRedoubles, $studentAnnuals, $department, $academicYear, $degree, $grade, $option) {

                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');
                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(0.25, 0.30, 0.25, 0.30));

                // Set all margins
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');

                $sheet->cells('A1:F1', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '16'
                    ));
                });
                $sheet->cells('A2:F2', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'size' => '12'
                    ));
                });
                $sheet->cells('A3:F3', function ($cells) {
                    $cells->setBackground('#C0C0C0 ');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    //$cells->setTextRotation(-90);
                    $cells->setFont(array(
                        'size' => '12',
                        'font' => 'bold'

                    ));
                });
                $sheet->row(2, ['', '', 'Department: ' . $department->name_en]);
                $sheet->row(3, ['No', 'ID-Card', 'Name-Latin', 'Sexe', 'Redouble']);
                $sheet->setCellValue('C1', $title);

                for ($key = 0; $key < 5; $key++) {
                    if ($alpha[$key] == 'C') {
                        $sheet->setWidth([$alpha[$key] => 25]);
                    } else {
                        $sheet->setSize($alpha[$key] . '3', 10, 20);
                    }
                }

                $count = 0;
                foreach ($studentAnnuals as $studentAnnual) {
                    if (!$studentAnnual->radie) {
                        if (isset($studentRedoubles[$studentAnnual->student_id])) {
                            $count++;
                            $row = [
                                $count,
                                $studentAnnual->id_card,
                                strtoupper($studentAnnual->name_latin),
                                $studentAnnual->code,
                                $studentRedoubles[$studentAnnual->student_id]->redouble_name
                            ];

                            $sheet->appendRow($row);
                        }
                    }


                }
            });

        })->download('xls');

    }

    private function studentRedoubleFromDB($studentIds, $academicYearId)
    {

        $students = [];
        $studentRedoubles = DB::table('redoubles')
            ->join('redouble_student', 'redoubles.id', '=', 'redouble_student.redouble_id')
            ->join('students', 'students.id', '=', 'redouble_student.student_id')
            ->whereIn('student_id', $studentIds)
            ->where('academic_year_id', $academicYearId)
            ->select(
                'redoubles.name_en as redouble_name', 'students.id as student_id', 'redouble_student.is_changed'
            )
            ->get();

        foreach ($studentRedoubles as $studentRedouble) {
            $students[$studentRedouble->student_id] = $studentRedouble;
        }

        return $students;
    }

    public function storeResitScore(Request $request)
    {

        $count = 0;
        $resitScores = $request->baseData;

        foreach ($resitScores as $score) {
            $courseAnnualId = $score['course_annual_id'];
            $store = $this->averages->updateResitScore($score);

            $this->courseAnnualScores->getUserLog($store, 'Average', 'Create-Resit-Score');

            if ($store) {
                $count++;
            }
        }
        if ($count == count($resitScores)) {

            $reDrawTable = $this->handsonTableData($courseAnnualId, $group = null);
            return Response::json(['status' => true, 'message' => 'Score Inserted', 'handsontableData' => $reDrawTable]);
        }

    }

    public function getKeyIssuedData(Request $request)
    {
        $this->validate($request, ['key' => 'required']);

        try {
            $config = Configuration::where('key', $request->key)->first();
            if ($config instanceof Configuration) {
                return json_encode(['config' => $config]);
            }
            return json_encode([]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getKeyIssuedDateStore(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);

        try {
            $config = Configuration::where('key', $request->key)->first();

            if ($config instanceof Configuration) {
                $config->value = $request->value;
                $config->update();
            } else {
                $config = Configuration::create($request->all());
            }
            return json_encode(['config' => $config]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getKeyIssuedAttestationDate(Request $request)
    {
        $this->validate($request, ['key' => 'required']);

        try {
            $config = Configuration::where('key', $request->key)->first();
            if ($config instanceof Configuration) {
                return json_encode(['config' => $config]);
            }
            return json_encode([]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getKeyIssuedDateAttestationStore(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);

        try {
            $config = Configuration::where('key', $request->key)->first();

            if ($config instanceof Configuration) {
                $config->value = $request->value;
                $config->update();
            } else {
                $config = Configuration::create($request->all());
            }

            return json_encode(['config' => $config]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    private function checkRedouble($student, $academicYearId)
    {

        if (!$student->radie) {

            $redouble_student = DB::table('redouble_student')->where([
                ['academic_year_id', $academicYearId],
                ['student_id', $student->student_id]
            ])->first();

            if ($redouble_student) {

                $redouble_student = (object)array_merge((array)$redouble_student, ['redouble_name' => $student->redouble_name]);
                return $redouble_student;

            } else {
                return null;//have no record of redouble
            }

        } else {

            return false;
        }

    }

    private function isArraysInterSected($array_1, $array_2)
    {
        $check = 0;


        if (count($array_1) == count($array_2)) {

            for ($index = 0; $index < count($array_1); $index++) {
                foreach ($array_2 as $ele) {
                    if ($array_1[$index] == $ele) {

                        return true;
                    }

                }
            }

            return false;
        } else {
            return false;
        }
    }
}
