<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseAnnual\CreateCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\DeleteCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\EditCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\StoreCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\UpdateCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\CourseAnnualAssignmentRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\GenerateCourseAnnualRequest;


use App\Models\Absence;
use App\Models\AcademicYear;
use App\Models\Average;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Enum\ScoreEnum;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Percentage;
use App\Models\StudentAnnual;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use App\Repositories\Backend\CourseAnnualScore\CourseAnnualScoreRepositoryContract;
use App\Repositories\Backend\Percentage\PercentageRepositoryContract;
use App\Repositories\Backend\Absence\AbsenceRepositoryContract;
use App\Repositories\Backend\CourseAnnualClass\CourseAnnualClassRepositoryContract;
use App\Repositories\Backend\CourseSession\CourseSessionRepositoryContract;

use App\Repositories\Backend\Average\AverageRepositoryContract;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\Course\CourseAnnual\ImportCourseAnnualRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CourseAnnual;
use App\Models\Semester;
use Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Support\Facades\Auth;
use App\Models\Enum\SemesterEnum;




class CourseAnnualController extends Controller
{
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
        CourseSessionRepositoryContract $courseSessionRepo

    )
    {
        $this->courseAnnuals = $courseAnnualRepo;
        $this->courseAnnualScores = $courseAnnualScoreRepo;
        $this->percentages = $percentageRepo;
        $this->absences = $absenceRepo;
        $this->averages = $averageRepo;
        $this->courseAnnualClasses = $courseAnnualClassRepo;
        $this->courseSessions = $courseSessionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(auth()->user()->allow("view-all-score-in-all-department")){
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $lecturers = Employee::lists("name_kh","id");
            $options = DepartmentOption::get();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $options = DepartmentOption::where('department_id',$employee->department_id)->get();
            if(auth()->user()->allow("view-all-score-course-annual")){ // This is chef department, he can see all courses in his department
                $lecturers = Employee::where('department_id',$department_id)->lists("name_kh","id");
            } else {
                $lecturers = null;
            }
        }

        $academicYears = AcademicYear::orderBy("id","desc")->lists('name_latin','id');
        $degrees = Degree::lists('name_en','id');
        $grades = Grade::lists('name_en','id');
        $semesters = Semester::orderBy('id')->lists('name_en', 'id');
        $studentGroup = StudentAnnual::select('group')->groupBy('group')->orderBy('group')->lists('group');

        return view('backend.course.courseAnnual.index',compact('departments','academicYears','degrees','grades', 'semesters', 'studentGroup','department_id','lecturers', 'options'));
    }

    private function deptHasOption($deptId) {

        $dept = Department::find($deptId);

        if($dept->department_options) {
            $deptOptions = $dept->department_options->lists('name_en', 'id');
        } else {
            $deptOptions = [];
        }

        return $deptOptions;
    }

    private function courseAnnualByDeptId ($deptId) {

        $coursePrograms = DB::table('courses')->where('department_id', $deptId)->lists('name_en', 'id');
//        $courseAnnuals = $courseAnnuals->where('course_annual_classes.department_id', $deptId)->lists('course_name', 'course_annual_id');

        return $coursePrograms;

    }

    public function getDeptOption(Request $request) {

        $deptOptions = $this->deptHasOption($request->department_id);
//        $coursePrograms = $this->courseAnnualByDeptId($request->department_id);

        return view('backend.course.courseAnnual.includes.dept_option_selection', compact('deptOptions'));

//        return [
//            'course' => view('backend.course.courseAnnual.includes.course_by_dept_selection', 'coursePrograms'),
//            'dept_option'  => view('backend.course.courseAnnual.includes.dept_option_selection', compact('deptOptions'))
//        ];

    }

    public function filteringStudentGroup(Request $request) {

        $groups = $this->getStudentGroupFromDB();

        if($deptId = $request->department_id) {
            $groups = $groups->where('studentAnnuals.department_id', '=',$deptId);
        }
        if($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=',$academicYearId);
        }
        if($degree_id = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=',$degree_id);
        }
        if($grade_id = $request->grade_id) {
            $groups = $groups->where('studentAnnuals.grade_id', '=',$grade_id);
        }
        if($option_id = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.department_option_id', '=',$option_id);
        }
        $groups = $groups->lists('group');
        asort($groups);
        $array_group = [];
        foreach($groups as $group) {
            $array_group[] = $group;
        }
        if($request->ajax()){
            return Response::json($array_group);
        } else {
            return view('backend.course.courseAnnual.includes.student_group_selection', compact('groups'))->render();
        }

    }


    public function getStudentGroupSelection(Request $request) {

        $groups = $this->getStudentGroupFromDB();

        if($deptId = $request->department_id) {
            $groups = $groups->where('studentAnnuals.department_id', '=',$deptId);
        }
        if($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=',$academicYearId);
        }
        if($degree_id = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=',$degree_id);
        }
        if($grade_id = $request->grade_id) {
            $groups = $groups->where('studentAnnuals.grade_id', '=',$grade_id);
        }
        if($option_id = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.department_option_id', '=',$option_id);
        }
        $groups = $groups->lists('group');

        return view('backend.course.courseAnnual.includes.student_group_selection', compact('groups'))->render();

    }

    private function getStudentGroupFromDB() {

        $groups = DB::table('studentAnnuals')
            ->select('group')
            ->groupBy('group')
            ->orderBy('group', 'ASC');

        return $groups;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseAnnualRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseAnnualRequest $request)
    {
        if(auth()->user()->allow("view-all-score-in-all-department")){
            $courses = Course::orderBy('updated_at', 'desc')->get();
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $options = DepartmentOption::get();
            $raw_courses = Course::join('degrees','degrees.id','=','courses.degree_id')
                ->join('departments','departments.id','=','courses.department_id')
                ->leftJoin('departmentOptions','departmentOptions.id','=','courses.department_option_id')
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
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $options = DepartmentOption::where('department_id',$employee->department_id)->get();
            $raw_courses = Course::where('courses.department_id', $department_id)
                                ->join('degrees','degrees.id','=','courses.degree_id')
                                ->join('departments','departments.id','=','courses.department_id')
                                ->leftJoin('departmentOptions','departmentOptions.id','=','courses.department_option_id')
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

        foreach($raw_courses as $raw_course){
            if(!isset($courses[$raw_course->department_code])){
                $courses[$raw_course->department_code] = array();
            }
            array_push($courses[$raw_course->department_code],$raw_course);
        }

        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $semesters = Semester::lists("name_kh", "id");
        return view('backend.course.courseAnnual.create',compact('departments','academicYears','degrees','grades','courses',"semesters", 'options'));
    }

    public function getDepts() {

        if(auth()->user()->allow("view-all-score-in-all-department")){
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = Department::where("parent_id",config('access.departments.department_academic'))
                ->whereNotIn('id', [$employee->department->id])
                ->orderBy("code")->lists("code","id");
        }
        return view('backend.course.courseAnnual.includes.other_dept_selection', compact('departments'));
    }

    public function getOtherLecturer(Request $request) {

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $currentTeachersInHisDept = $this->getAllteacherByDeptId($employee->department->id);
        if($request->department_id) {
            $teacherByDept = $this->getAllteacherByDeptId($request->department_id);
        } else {
            $teacherByDept = [];
        }

        $totalTeachers = array_merge($currentTeachersInHisDept, $teacherByDept);

//        dd($totalTeachers);
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
        $data = $request->all();

        $storeCourseAnnual = $this->courseAnnuals->create($data);

        if($storeCourseAnnual) {
            //----create score percentage ----
            $this->createScorePercentage($request->midterm_score, $request->final_score, $storeCourseAnnual->id);

            $data = $data + ['course_annual_id' => $storeCourseAnnual->id];
            $storeCourseAnnualClass = $this->courseAnnualClasses->create($data);

            if($storeCourseAnnualClass) {
                return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
            }
        }

        return redirect()->back()->withFlashSuccess('Create Error!');
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditCourseAnnualRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCourseAnnualRequest $request, $id)
    {

        $groups = [];
        $courseAnnual = $this->courseAnnuals->findOrThrowException($id);
        $scores = $this->getPropertiesFromScoreTable($courseAnnual->id)->get();

        $arrayPercentages =[];
        foreach($scores as $score) {
            $arrayPercentages[$score->percentage_id][] =$score;
        }


        foreach($arrayPercentages as $key => $percentage) {

            $explode = explode('-', $percentage[0]->name);
            if(strtolower($explode[0]) == strtolower(ScoreEnum::Name_Mid)) {
                $midterm['percentage']= $percentage[0]->percent;
                $midterm['percentage_id']= $key;
            }

            if(strtolower($explode[0]) == strtolower(ScoreEnum::Name_Fin)){
                //----find midterm from final value
                $final['percentage']= $percentage[0]->percent;
                $final['percentage_id']= $key;
            }
        }

        $array_groups = $this->getStudentGroupFromDB()
                       ->where('studentAnnuals.department_id', '=',$courseAnnual->department_id)
                       ->where('studentAnnuals.academic_year_id', '=',$courseAnnual->academic_year_id)
                       ->where('studentAnnuals.degree_id', '=',$courseAnnual->degree_id)
                       ->where('studentAnnuals.grade_id', '=',$courseAnnual->grade_id)
                       ->where('studentAnnuals.department_option_id', '=',$courseAnnual->department_option_id)
                       ->lists('group');
        asort($array_groups);
        foreach($array_groups as $group) {
            $groups[] = $group;
        }

        if(auth()->user()->allow("view-all-score-in-all-department")){
            $courses = Course::orderBy('updated_at', 'desc')->get();
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $options = DepartmentOption::get();
            $raw_courses = Course::join('degrees','degrees.id','=','courses.degree_id')
                ->join('departments','departments.id','=','courses.department_id')
                ->leftJoin('departmentOptions','departmentOptions.id','=','courses.department_option_id')
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
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $options = DepartmentOption::where('department_id',$employee->department_id)->get();
            $raw_courses = Course::where('courses.department_id', $department_id)
                ->join('degrees','degrees.id','=','courses.degree_id')
                ->join('departments','departments.id','=','courses.department_id')
                ->leftJoin('departmentOptions','departmentOptions.id','=','courses.department_option_id')
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

        foreach($raw_courses as $raw_course){
            if(!isset($courses[$raw_course->department_code])){
                $courses[$raw_course->department_code] = array();
            }
            array_push($courses[$raw_course->department_code],$raw_course);
        }

        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $semesters = Semester::lists("name_kh", "id");

        return view('backend.course.courseAnnual.edit',compact('courseAnnual','departments','academicYears','degrees','grades','courses', 'options','semesters', 'groups', 'midterm', 'final'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseAnnualRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseAnnualRequest $request, $id)
    {

        $check = 0;
        $input = $request->all();
        $midterm_id = $request->midterm_percentage_id;
        $final_id = $request->final_percentage_id;

        $midterm  = [
            'name'  => 'Midterm-'.$request->midterm_score.'%',
            'percent' => (int)$request->midterm_score,
        ];

        $final = [
            'name'  => 'Final-'.$request->final_score.'%',
            'percent' => (int)$request->final_score,
        ];

        $updateMidterm = $this->percentages->update($midterm_id, $midterm);

        if($updateMidterm) {
            $updateFinal = $this->percentages->update($final_id, $final);
            if($updateFinal) {

                $updateCourseAannual = $this->courseAnnuals->update($id, $input);

                if($updateCourseAannual) {

                    $delete = DB::table('course_annual_classes')->where([
                        ['course_annual_id',$updateCourseAannual->id],
                        ['course_session_id',null],
                    ]);

                    $data = [
                        'groups'                => $request->groups,
                        'course_annual_id'      => $updateCourseAannual->id
                    ];
                    if(count($delete->get()) > 0) {
                        $delete =  $delete->delete();
                        if($delete) {
                            $create = $this->courseAnnualClasses->create($data);
                        }

                    } else {

                        $create = $this->courseAnnualClasses->create($data);
                    }

                    if($create) {
                        return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
                    }

                }

                return redirect()->back()->withFlashError('Not Updated');
            }
        }





    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCourseAnnualRequest $request, $id)
    {
        $this->courseAnnuals->destroy($id);
        return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data(Request $request)
    {

        $courseAnnuals = CourseAnnual::leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('academicYears','course_annuals.academic_year_id', '=', 'academicYears.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->leftJoin('grades','course_annuals.grade_id', '=', 'grades.id')
            ->leftJoin('semesters','course_annuals.semester_id', '=', 'semesters.id')
            ->leftJoin('departmentOptions', 'course_annuals.department_option_id', '=', 'departmentOptions.id')
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
                'employees.name_latin as employee_id',
                'academicYears.name_kh as academic_year',
                'semesters.name_kh as semester',
                'course_annuals.course_id',
                DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
            ])
            ->orderBy("courses.degree_id","ASC")
            ->orderBy("courses.department_id","ASC")
            ->orderBy("courses.grade_id","ASC")
            ->orderBy("course_annuals.semester_id","ASC");

        $datatables =  app('datatables')->of($courseAnnuals);
        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $datatables
            ->addColumn('mark', function($courseAnnual){
                return "<img class='image_mark' src='".url('img/arrow.png')."' />";
            })
            ->editColumn('name', function($courseAnnual) {
                ob_start();
                ?>
                <div class="row">
                    <div class="col-md-9">
                        <span style="display: none" class="course_id"><?php echo $courseAnnual->id ?></span>
                        <h4><?php echo $courseAnnual->name ?></h4>
                        <span>(C=<?php echo $courseAnnual->time_course?> | TD=<?php echo $courseAnnual->time_td ?> | TP= <?php echo $courseAnnual->time_tp ?>)</span>
                    </div>
                    <div class="col-md-3">
                        <?php echo $courseAnnual->class ?>
                        <br/>
                        <?php
                        foreach($courseAnnual->courseAnnualClass as $obj_group) {
                            echo $obj_group->group." ";
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $courseAnnual->semester." | ".$courseAnnual->academic_year ?>
                    </div>
                </div>
                <?php
                $html = ob_get_clean();
                return $html;
            })
            ->editColumn('class', function ($courseAnnual) {
                $course_annual_classes = DB::table('course_annual_classes')

                    ->select('group')
                    ->where('course_annual_classes.course_annual_id',$courseAnnual->id)
                    ->get();

                $data = "";

                foreach($course_annual_classes as $obj){
                    $data = $obj->group.'.'.$data;
                }

                if($data != ".") {
                    return $courseAnnual->class.'-'.$data;
                } else {
                    return $courseAnnual->class;
                }

            })
            ->addColumn('action', function ($courseAnnual) use ($employee) {
                if(Auth::user()->id == 1) { // This is admin
                    return  '<a href="'.route('admin.course.form_input_score_course_annual',$courseAnnual->id).'" class="btn btn-xs btn-info input_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.'input score'.'"></i></a>'.
                            ' <a href="'.route('admin.course.course_annual.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                            ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                } else {
                    $my_courses = CourseAnnual::where('employee_id',$employee->id)->lists('id')->toArray();

                    $actions = "";

                    // Check if this is his/her course and he/she has permission to input score
                    if(Auth::user()->allow('input-score-course-annual')) {
                        if(in_array($courseAnnual->id,$my_courses)){
                            $actions = $actions.'<a href="'.route('admin.course.form_input_score_course_annual',$courseAnnual->id).'" class="btn btn-xs btn-info input_score_course"><i class="fa fa-area-chart" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.'input score'.'"></i></a>';
                        }
                    }

                    if(Auth::user()->allow('edit-courseAnnuals')) {
                        $actions = $actions.' <a href="'.route('admin.course.course_annual.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
                    }

                    if(Auth::user()->allow('delete-courseAnnuals')) {
                        $actions = $actions.' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                    }

                    return $actions;

                }

            });
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('course_annuals.academic_year_id', '=', $academic_year);
        } else {
            $last_academic_year_id =AcademicYear::orderBy('id','desc')->first()->id;
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

        if($deptOption = $datatables->request->get('dept_option')) {
            $datatables->where('course_annuals.department_option_id', '=', $deptOption);
        }
        if($group = $datatables->request->get('student_group')) {
            $datatables->where('course_annual_classes.group', '=', $group);
        }

        if(auth()->user()->allow("view-all-score-in-all-department")){ // user has permission to view all course/score in all department
            if ($department = $datatables->request->get('department')) {
                $datatables->where('course_annuals.department_id', '=', $department);
            }
        } else {
            $datatables = $datatables ->where('course_annuals.department_id', $employee->department->id );
        }

        if(auth()->user()->allow("view-all-score-course-annual")){   // This one is might be chef department, he can view all course/score for all teacher
            if ($lecturer = $datatables->request->get('lecturer')) {
                $datatables->where('course_annuals.employee_id', '=', $lecturer);
            }
        } else {
            $datatables = $datatables ->where('course_annuals.employee_id', $employee->id );
        }

        $datatables = $datatables->get();

        return $datatables->make(true);
    }
    public function request_import(){
        return view('backend.course.courseAnnual.import');
    }

    public function import(ImportCourseAnnualRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');
        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;
            DB::beginTransaction();
            try{
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function($results){
                    $results->each(function($row) {
                        // Clone an object for running query in studentAnnual
                        $courseAnnual_data = $row->toArray();
                        $courseAnnual_data["created_at"] = Carbon::now();
                        $courseAnnual_data["create_uid"] = auth()->id();
                        $courseAnnual = CourseAnnual::create($courseAnnual_data);
                        $first = false;
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();
            return redirect(route('admin.backend.course.course_annual.index'));
        }
    }


    private function getCourseAnnually() {

        $courseAnnuals = DB::table('course_annuals')
//            ->join('courses', 'courses.id', '=', 'course_annuals.course_id')
//            ->join('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
//            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->select(
                'course_annuals.name_en as course_name',
                'course_annuals.id as course_annual_id',
                'course_annuals.course_id as course_id',
                'departments.id as department_id',
                'degrees.id as degree_id',
                'course_annuals.grade_id',
                'course_annuals.time_tp',
                'course_annuals.time_td',
                'course_annuals.time_course',
                'course_annuals.semester_id',
//                'courses.code as code',
//                'courses.credit as course_program_credit',
//                'course_annual_classes.group',
                'course_annuals.employee_id',
                'course_annuals.active',
                'course_annuals.academic_year_id',
                'course_annuals.credit as course_annual_credit',
//                'course_annuals.credit as course_credit',
                'degrees.code as degree_code'
//                'course_annual_classes.id as course_annual_class_id'
            );

//        dd($courseAnnuals->where('course_annual_classes.department_id', 1)->get());

        return $courseAnnuals;
    }


    private function getCourseAnnualFromDB ($teacherId, $academicYearId, $grade_id, $degree_id) {

        $courseAnnuals = $this->getCourseAnnually();
        $courseAnnuals = $courseAnnuals->where('employees.id', $teacherId);
        $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', $academicYearId);
        if($degree_id) {
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.degree_id', '=',$degree_id);
        }
        if($grade_id) {
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.grade_id', '=',$grade_id);
        }
        $courseAnnuals = $courseAnnuals->get();
        return $courseAnnuals;

    }

    private function courseSessionByTeacherFromDB($academicYearId, $grade_id, $degree_id) {

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

//        dd($courseSessions);

        foreach($courseSessions as $courseSession) {
            if($courseSession->lecturer_id != null) {
                $arrayCourses[$courseSession->lecturer_id][] = $courseSession;
            }

        }
        return $arrayCourses;
    }

    private function getCourseSessionFromDB() {

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

    private function getNotSelectedCourseByDept($deptId, $academicYearId, $grade_id, $degree_id, $dept_option_id, $semester_id) {

//        $courseAnnuals = $this->getCourseAnnually();

        $courseSessions = $this->getCourseSessionFromDB();

//        dd($deptId.'--'.$grade_id. '--'. $degree_id. '--'.$dept_option_id .'--'. $semester_id);
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
//        $courseAnnuals = $courseAnnuals->get();
        $courseSessions = $courseSessions->get();


//        dd($courseSessions);
//        $array =[];
//        foreach($courseAnnuals as $courseAnnual) {
//            $array[$courseAnnual->course_annual_id][] = $courseAnnual;
//        }

        return $courseSessions;

    }

    private function getAllteacherByDeptId ($deptID) {

        $allTeachers = DB::table('employees')
            ->select('employees.name_kh as teacher_name', 'employees.id as teacher_id', 'employees.department_id as department_id')
            ->where('employees.department_id', $deptID)
            ->orderBy('teacher_name')
            ->distinct('BINARY employees.name_kh')
            ->get();

        return $allTeachers;

    }

    public function getAllDepartments(CourseAnnualAssignmentRequest $request) {

//        dd($request->all());

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

                    return Response::json(array($element));
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

        return Response::json($allDepartments);
    }

    public function getAllTeacherByDepartmentId (CourseAnnualAssignmentRequest $request) {

        $teachers = [];
        $department_id = explode('_', $_GET['id'])[1];
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
//                    dd($totalCoursePerSemester);
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

        return Response::json($teachers);

    }


    private function getGroupBySessionAndAnnualCourse() {

        $array =[];
        $groups = DB::table('course_annual_classes')->get();

        foreach($groups as $group) {
            if($group->course_session_id != null) {
                $array[$group->course_session_id][] = $group;
            }
        }
        return ($array);

    }

    public function getSeletedCourseByTeacherID(CourseAnnualAssignmentRequest $request) {

        $courses = [];
        $arayCourse =[];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $parent_id = $_GET['id'];
        $teacher_id = explode('_', $_GET['id'])[3];

        $arrayCourseSelected=[];


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

//            $res = array_map("unserialize", array_unique(array_map("serialize", $courses)));
//
//            foreach ($res as $a) {
//                array_push($arayCourse, $a);
//            }

            return Response::json($courses);
        }

    }

    private function formatGroupName($listsGroup) {

//        dd($listsGroup);


//        $listsGroup = array_reverse($listsGroup);
        $name = '';
        foreach($listsGroup as $group) {
            $name = $name.' '.$group->group;
        }

        return $name;
    }

    public function getAllCourseByDepartment (Request $request) {

        //CourseAnnualAssignmentRequest

//        dd($request->all());

        $deptId = explode('_', $_GET['id'])[1];
        $arrayCourses = [];
        $Course = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $dept_option_id = $request->department_option_id;
        $semester_id = $request->semester_id;

        $notSelectedCourses = $this->getNotSelectedCourseByDept($deptId, $academic_year_id, $grade_id, $degree_id, $dept_option_id, $semester_id);
        $groupFromDB = $this->getGroupBySessionAndAnnualCourse();

//        dd($notSelectedCourses);
        if($notSelectedCourses) {

            foreach($notSelectedCourses as $course) {

                $totalCoursePerSemester = $course->time_tp_session + $course->time_td_session + $course->time_course_session;

                $splitName = explode('_', $course->name_en);
                $copy = $splitName[count($splitName)-1];

//                dd($groupFromDB);
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

//        dd(Response::json($arrayCourses));
//        $res = array_map("unserialize", array_unique(array_map("serialize", $arrayCourses)));
//
//        foreach ($res as $a) {
//            array_push($Course, $a);
//        }

        return Response::json($arrayCourses);

    }

    public function studentGroupByDept(Request $request) {

        $arrayGroup = [];
        $nodeId = explode('_', $_GET['id']);
        $deptId = $nodeId[count($nodeId)-1];
        $groups = $this->getStudentGroupFromDB();
        $groups = $groups->where('studentAnnuals.department_id', '=',$deptId);

        if($academicYearId = $request->academic_year_id) {
            $groups = $groups->where('studentAnnuals.academic_year_id', '=',$academicYearId);
        }
//        if($semesterId = $request->semester_id) {
//            $groups = $groups->where('studentAnnuals.semester_id', '=',$semesterId);
//        }
        if($gradeId = $request->grade_id) {
            $groups = $groups->where('studentAnnuals.grade_id', '=',$gradeId);
        }
        if($degreeId = $request->degree_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=',$degreeId);
        }
        if($deptOptionId = $request->department_option_id) {
            $groups = $groups->where('studentAnnuals.degree_id', '=',$degreeId);
        }
        $groups = $groups->get();

        dd($groups);

        usort($groups, function($a, $b) {
            return $a->group - $b->group;
        });

        if(count($groups) > 0) {
            foreach($groups as $group) {
                echo($group->group);
                dd($groups);
                if($group->group != null) {


                    $element = [
                        'id' => 'department_'.$deptId.'_'.$group->group,
                        'text' => (($degreeId == 1)?'I':'T ').$gradeId.'_'.$group->group,
                        'li_attr' => [
                            'class' => 'student_group'
                        ],
                        "type" => "group",
                        "state" => ["opened" => false, "selected" => false ]

                    ];

                    $arrayGroup[] = $element;
                }
            }

        }

        dd(Response::json($arrayGroup));
        return Response::json($arrayGroup);
    }

    public function courseAssignment (CourseAnnualAssignmentRequest $request) {


        $academicYear = AcademicYear::where('id', $request->academic_year_id)->first();
        $departmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $deptOption = $request->department_option_id;
        $semesterId = $request->semester_id;
        $departmentOptions = $this->deptHasOption($departmentId);

        if(auth()->user()->allow("view-all-score-in-all-department")){
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $user_department_id = null;

        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code", "id");
            $user_department_id = $employee->department->id;


        }
        $academicYears = AcademicYear::lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_en','id')->toArray();
        $grades = Grade::lists('name_en','id')->toArray();
        $semesters = Semester::lists("name_en", "id");

        if($deptOption == '') {
            $deptOption = null;
        }

//        dd($departmentOptions);
        return view('backend.course.courseAnnual.includes.popup_course_assignment', compact(
            'academicYear', 'departmentId', 'gradeId','academicYears', 'degrees', 'grades', 'semesters','departmentOptions','departments','user_department_id',
            'degreeId', 'deptOption', 'semesterId'));
    }


    public function removeCourse (CourseAnnualAssignmentRequest $request) {

        $input = $request->course_selected;
        $nodeID = json_decode($input);

        if(count($nodeID) > 0) {
            $check = 0;
            $uncount =0;

            foreach ($nodeID as $id) {
                $explode = explode('_', $id);
                if(count($explode) > 4) {// the node id here will return the above node so we want to delete only the depest node (course) then the id of each course length must greater than 4
//                    $deparment_id = $explode[1];
//                    $teacher_id =  $explode[3];
                    $course_session_id = $explode[5];
                    $update = $this->updateCourse($course_session_id, $inputs = '');

                    if($update) {
                        $check++;
                    }

                } else {
                    $uncount++;
                }
            }
        }

        if($check == (count($nodeID) - $uncount) ) {

            return Response::json(['status' => true, 'message' => 'You Have Removed Selected Courses']);

        }

    }

    private function updateCourse($courseSessionId, $input) {


        $courseSession = $this->courseSessions->findOrThrowException((int)$courseSessionId);
//        $courseAnnual->active = isset($input['active'])?true:false;
        $courseSession->lecturer_id = isset($input['lecturer_id'])?$input['lecturer_id']:null;
        $courseSession->write_uid = auth()->id();

        if ($courseSession->save()) {
            return true;
        }

         throw new GeneralException(trans('exceptions.backend.general.update_error'));

    }

    public function assignCourse(CourseAnnualAssignmentRequest $request) {

        $arrayCourseId = $request->course_id;
        $arrayTeacherId = $request->teacher_id;
        $check =0;
        $uncount =0;
        $index=0;

        if(count($arrayTeacherId) > 0) {

            if(count($arrayCourseId) >0 ) {


                foreach($arrayTeacherId as $teacher) {

                    $teacherId = explode('_', $teacher);

                    if(count($teacherId) == 4) {
                        $lecturer_id = $teacherId[3];

                        foreach($arrayCourseId as $course) {

                            $courseId = explode('_', $course);

                            if(count($courseId) == 4) {

                                $course_session_id = $courseId[3];

                                $input = [
                                    'active' => true,
                                    'lecturer_id' => $lecturer_id,
                                ];
                                $res = $this->updateCourse($course_session_id, $input);

                                if($res) {
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

                if((count($arrayTeacherId)- $uncount) != 0) {
                    if($check == ( count($arrayCourseId) - $index ) * (count($arrayTeacherId)- $uncount) ) {

                        return Response::json(['status' => true, 'message' => 'Course Added']);

                    } else {
                        return Response::json(['status' => false, 'message' => 'Course Not Added!!']);
                    }
                } else {
                    return Response::json(['status' => false, 'message' => 'Teacher Not Selected!!']);
                }

            } else {
                return Response::json(['status' => false, 'message' => 'Not Selected Course!!']);
            }

        } else {
            return Response::json(['status' => false, 'message' => 'Not Seleted Teacher!']);
        }
    }


    public function formEditCourseAnnual(CourseAnnualAssignmentRequest $request) {


//        dd($request->all());
        $explode = explode('_', $request->dept_course_id);// department with course session id concatination
        $courseSessionId = $explode[3];
        $deptId = $explode[1];

        $courseSession = DB::table('course_sessions')->where('id', $courseSessionId)->first();

        $courseAnnual = DB::table('course_annuals')->where('id', $courseSession->course_annual_id)->first();

        $courseAnnualClasses = DB::table('course_annual_classes')->where([
            ['course_annual_id', $courseSession->course_annual_id],
            ['course_session_id', $courseSessionId]
        ])->get();

        $allSemesters = Semester::get();
        $allGroups = DB::table('course_annual_classes')->where([
            ['course_annual_id', $courseAnnual->id],
            ['course_session_id', null]
        ]);

        if(count($allGroups->get()) > 1) {

            $allGroups = $allGroups->orderBy('group')->lists('group', 'group');
        } else {
            foreach($allGroups->get() as $group) {
                if($group->group == null) {
                    $allGroups = DB::table('studentAnnuals')->where([
                        ['department_id', $deptId],
                        ['academic_year_id', $courseAnnual->academic_year_id],
                        ['grade_id', $courseAnnual->grade_id],
                        ['degree_id', $courseAnnual->degree_id],
                    ])->orderBy('group')->lists('group', 'group');

                    break;
                }
            }
        }

        asort($allGroups);

        if($courseAnnual) {

            return view('backend.course.courseAnnual.includes.popup_edit_course_annual', compact('allSemesters', 'allGroups', 'courseAnnualClasses', 'courseSession'));
        }

    }

    public function editCourseAnnual($courseSessionId, CourseAnnualAssignmentRequest $request) {


//        dd($request->all());
        $groups = $request->group;
        $courseSession = DB::table('course_sessions')->where('id', $courseSessionId)->first();
        $courseAnnual = DB::table('course_annuals')->where('id', $courseSession->course_annual_id)->first();
        $inputs = [
            'time_course'   => $request->time_course,
            'time_td'       => $request->time_td,
            'time_tp'       => $request->time_tp,
        ];

        $update =  $this->courseSessions->update($courseSessionId, $inputs);
        if($update) {
            $delete = DB::table('course_annual_classes')->where([
                ['course_annual_id',$courseSession->course_annual_id],
                ['course_session_id',$courseSession->id],
            ]);

            $data = [
                'department_id'         => $courseAnnual->department_id,
                'grade_id'              => $courseAnnual->grade_id,
                'degree_id'             => $courseAnnual-> degree_id,
                'department_option_id'  => $courseAnnual->department_option_id,
                'groups'                => $groups,
                'course_annual_id'      => $courseAnnual->id,
                'course_session_id'      => $update->id
            ];
            if(count($delete->get()) > 0) {

                $delete =  $delete->delete();
                if($delete) {
                    $create = $this->courseAnnualClasses->create($data);
                }

            } else {

                $create = $this->courseAnnualClasses->create($data);
            }

            if($create) {
                return Response::json(['status'=>true, 'message'=>'update course successfully!', 'selected_element' => 'department_'.$courseAnnual->department_id.'_course_' . $courseAnnual->id,]);
            }

        } else {
            return Response::json(['status'=>false, 'message'=>'Error Updating!!']);
        }
    }

    public function douplicateCourseAnnual(CourseAnnualAssignmentRequest $request) {

        $explode = explode('_',$request->dept_course_id);
        $courseAnnualId = $explode[3];
        $couseSesssionId = $explode[3];
        $courseSession = DB::table('course_sessions')->where('id',$couseSesssionId)->first();
        $courseAnnual = DB::table('course_annuals')->where('id', $courseSession->course_annual_id)->first();
        $courseAnnualClasses = DB::table('course_annual_classes')->where([
            ['course_annual_id', $courseAnnualId],
            ['course_session_id', $couseSesssionId]
        ])->lists('group');

        $inputs = [
            'time_course'   => $courseAnnual->time_course,
            'time_td'       => $courseAnnual->time_td,
            'time_tp'       => $courseAnnual->time_tp,
            'course_annual_id'     => $courseSession->course_annual_id
        ];
        $save =  $this->courseSessions->create($inputs);
        if($save) {

            $data = [
                'groups'                 => $courseAnnualClasses,
                'department_option_id'  => $courseAnnual->department_option_id,
                'department_id'         => $courseAnnual->department_id,
                'degree_id'             => $courseAnnual->degree_id,
                'grade_id'              => $courseAnnual->grade_id,
                'course_annual_id'      => $courseAnnual->id,
                'course_session_id'      => $save->id
            ];

            $saveCourseAnnualClass = $this->courseAnnualClasses->create($data);

            if ($saveCourseAnnualClass) {
                return Response::json(['status'=>true, 'message'=>'Course Duplicated!']);
            } else {
                return Response::json(['status'=>false, 'message'=>'Error Duplicated!']);
            }
        }
    }

    public function deleteCourseAnnual(CourseAnnualAssignmentRequest $request) {

        $explode = explode('_',$request->dept_course_id);
        $courseSessionId = $explode[3];
        $courseSession = DB::table('course_sessions')->where('id', $courseSessionId)->first();
        $courseAnnualClasses = DB::table('course_annual_classes')->where([
            ['course_annual_id', $courseSession->course_annual_id],
            ['course_session_id', $courseSession->id]
        ])->delete();
        if($courseAnnualClasses) {
            $delete = $this->courseSessions->destroy($courseSessionId);
            if($delete) {
                return Response::json(['status'=>true, 'message'=>'Successfully Deleted!']);
            } else {
                return Response::json(['status'=>true, 'message'=>'Error Deleted!']);
            }
        }

    }

    private function getCourseAnnualById ($courseAnnualId) {

        $courseAnnual = DB::table('course_annuals')
            ->leftJoin('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
            ->where('course_annuals.id', $courseAnnualId)
//            ->select('course_annuals.id as course_annual_id', 'course_annuals.academic_year_id', 'course_annuals.semester_id',
//                'course_annuals.employee_id', 'course_annuals.name_en', 'course_annuals.name_kh', 'course_annuals.name_fr',
//                'course_annuals.credit', 'course_annuals.course_id', 'course_annuals.time_course', 'course_annuals.time_td', 'course_annuals.time_tp',
//                'course_annual_classes.grade_id', 'course_annual_classes.degree_id', 'course_annual_classes.department_id', 'course_annual_classes.department_option_id',
//                'course_annual_classes.group', 'departments.code as department_code',
//                'grades.name_en as grade_name'
//
//            )
            ->first();

//        $course = CourseAnnual::where('id', $courseAnnualId)->first();

        return $courseAnnual;

    }


    public function generateCourseAnnual(Request $request) {

        $courseAnnual= DB::table('course_annuals')
            ->join('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
            ->where('academic_year_id', $request->academic_year_id-1);
//        dd($request->all());
        $departmentId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $check =0;
        $unCheck=0;
        $countIsGenerated =0;

        if($departmentId) {
            $courseAnnual = $courseAnnual->where('course_annual_classes.department_id', '=', $departmentId);
        }
        if($degreeId) {
            $courseAnnual = $courseAnnual->where('course_annual_classes.degree_id', '=', $degreeId);
        }
        if($gradeId) {
            $courseAnnual = $courseAnnual->where('course_annual_classes.grade_id', '=', $gradeId);
        }
        $courseAnnual = $courseAnnual->get();

        foreach($courseAnnual as $course) {
            $input = [
                'time_course'   => $course->time_course,
                'time_td'       => $course->time_td,
                'time_tp'       => $course->time_tp,
                'name_kh'       => $course->name_kh,
                'name_en'       => $course->name_en,
                'name_fr'       => $course->name_fr,
                'course_id'     => $course->course_id,
                'semester_id'   => $course->semester_id,
                'active'        => true,
                'academic_year_id'      => $request->academic_year_id,
                'department_id'         => $course->department_id,
                'degree_id'             => $course->degree_id,
                'grade_id'              => $course->grade_id,
                'employee_id'           => $course->employee_id,
            ];

            // check for preventing a sencond time of generating Course Annual

            $isGenerated = $this->isCourseAnnualGenerated($course->course_id,$course->semester_id,$request->academic_year_id,$course->department_id, $course->degree_id,$course->grade_id, $course->employee_id);

            if($isGenerated) {
                $unCheck++;
                continue;

//                return Response::json(['status'=> false, 'message'=>'Duplicated Generating!!']);

            } else {
                $store = $this->courseAnnuals->create($input);

                if($store) {
                    $check++;
                }
            }
        }

        if($check == count($courseAnnual) - $unCheck) {
            return Response::json(['status'=> true, 'message'=>'Course Annual Generated!!']);

        } else {
            return Response::json(['status'=> true, 'message'=>'Course Annual Not Generated!!']);
        }


    }

    private function isCourseAnnualGenerated($courseId, $semesterId, $academicYearId, $departmentId, $degreeId, $gradeId, $employeeId) {

        $select = DB::table('course_annuals')
            ->join('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
            ->where([
                ['course_id', '=', $courseId],
                ['semester_id', '=', $semesterId],
                ['academic_year_id', '=', $academicYearId],
                ['course_annual_classes.department_id', '=', $departmentId],
                ['course_annual_classes.degree_id', '=', $degreeId],
                ['course_annual_classes.grade_id', '=', $gradeId],
                ['course_annual_classes.employee_id', '=', $employeeId]
            ])
            ->get();
        if($select) {
//            dd($select);
            return true;
        } else {
            return false;
        }

    }

    private function getAvailableCourse($deptId, $academicYearId, $semesterId) {

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

    private function dataSendToView($courseAnnualId) {

        $courseAnnual = DB::table('course_annuals')->where('id', $courseAnnualId)->first();

        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $availableCourses = $this->getAvailableCourse($courseAnnual->department_id, $courseAnnual->academic_year_id, $courseAnnual->semester_id);

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

    public function getFormScoreByCourse(Request $request, $courseAnnualId) {


        $properties = $this->dataSendToView($courseAnnualId);
        $courseAnnual = $properties['course_annual'];
        $availableCourses = $properties['available_course'];

        return view('backend.course.courseAnnual.includes.form_input_score_course_annual', compact('courseAnnualId', 'courseAnnual', 'availableCourses'));
    }

    public function getCourseAnnualScoreByAjax(Request $request) {

        //-----this is a default columns and columnHeader

        return $this->handsonTableData($request->course_annual_id);
    }


    private function handsonTableHeaders($columnName) {

        $columnHeader = array(/*'Student_annual_id',*/'Student ID', 'Student Name', 'M/F', 'Abs', 'Abs-10%');
        $columns=  array(
//            ['data' => 'student_annual_id', 'readOnly'=>true],
            ['data' => 'student_id_card', 'readOnly'=>true],
            ['data' => 'student_name', 'readOnly'=>true],
            ['data' => 'student_gender', 'readOnly'=>true],
            ['data' => 'num_absence', 'type' => 'numeric'],
            ['data' => 'absence', 'type' => 'numeric', 'readOnly'=>true],
        );
        $colWidths = [80,180,55, 55, 55];
        if($columnName) {

            foreach($columnName as $column) {
                $columnHeader = array_merge($columnHeader, array($column->name));
                $columns = array_merge($columns, array(['data'=>$column->name]));
                $colWidths[] = 70;
            }
            $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true, 'type'=> 'numeric'], ['data'=> 'notation']));
            $columnHeader = array_merge($columnHeader, array('Total', 'Notation'));
            $colWidths[] = 70;

        } else {

            $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true], ['data'=> 'notation']));
            $columnHeader = array_merge($columnHeader, array('Average' ,'Notation'));
            $colWidths[] = 70;
        }

        return [
            'colHeader' => $columnHeader,
            'column'  => $columns,
            'colWidth' => $colWidths
        ];

    }

    private function arrayIdsOfDeptGradeDegreeDeptOption($courseAnnualId) {


        $department_ids = DB::table('course_annuals')->where('id', $courseAnnualId)->lists('department_id');
        $grade_ids = DB::table('course_annuals')->where('id', $courseAnnualId)->lists('grade_id');
        $degree_ids = DB::table('course_annuals')->where('id', $courseAnnualId)->lists('degree_id');
        $departmentOptionIds = DB::table('course_annuals')->where('id', $courseAnnualId)->lists('department_option_id');

        $department_option_ids = [];
        foreach($departmentOptionIds as $optionId) {
            if($optionId != null) {
                $department_option_ids[] = $optionId;
            }
        }

//        $departments = DB::table('course_annual_classes')
//            ->distinct('department_id')
//            ->select('department_id')
//            ->where('course_annual_id',$courseAnnualId)
//            ->groupBy('department_id')->get();
//
//        $department_ids = array();
//        foreach ($departments as $department){
//            array_push($department_ids,$department->department_id);
//        }

//        $grades = DB::table('course_annual_classes')
//            ->distinct('grade_id')
//            ->select('grade_id')
//            ->where('course_annual_id',$courseAnnualId)
//            ->groupBy('grade_id')->get();
//        $grade_ids = array();
//        foreach ($grades as $grade){
//            array_push($grade_ids,$grade->grade_id);
//        }
//
//        $degrees = DB::table('course_annual_classes')
//            ->distinct('degree_id')
//            ->select('degree_id')
//            ->where('course_annual_id',$courseAnnualId)
//            ->groupBy('degree_id')->get();
//
//        $degree_ids = array();
//        foreach ($degrees as $degree){
//            array_push($degree_ids,$degree->degree_id);
//        }
//
//
//        $departmentOptions = DB::table('course_annual_classes')
//            ->distinct('department_option_id')
//            ->select('department_option_id')
//            ->where('course_annual_id',$courseAnnualId)
//            ->groupBy('department_option_id')->get();
//
//        $department_option_ids = array();
//        foreach ($departmentOptions as $departmentOption){
//            if($departmentOption->department_option_id != null) {
//                array_push($department_option_ids,$departmentOption->department_option_id);
//            }
//
//        }


        $array_groups = [];
        $groups = DB::table('course_annual_classes')
            ->select('group')
            ->where('course_annual_id',$courseAnnualId)
            ->lists('group');
        foreach($groups as $group) {
            if($group !=null) {
                $array_groups[] = $group;
            }
        }

        return [
            'department_id'=> $department_ids,
            'grade_id'  => $grade_ids,
            'degree_id' => $degree_ids,
            'department_option_id'  => $department_option_ids,
            'group'     => $array_groups
        ];
    }

    private function handsonTableData($courseAnnualId) {


        $arrayData = [];
        $courseAnnual = DB::table('course_annuals')->where('id', $courseAnnualId)->first();

        $arrayIdsOfDeptDegreeGradeDeptOption = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnualId);

        $department_ids = $arrayIdsOfDeptDegreeGradeDeptOption['department_id'];
        $degree_ids = $arrayIdsOfDeptDegreeGradeDeptOption['degree_id'];
        $grade_ids = $arrayIdsOfDeptDegreeGradeDeptOption['grade_id'];
        $department_option_ids = $arrayIdsOfDeptDegreeGradeDeptOption['department_option_id'];
        $groups = $arrayIdsOfDeptDegreeGradeDeptOption['group'];


        $columnName = $this->getPropertiesFromScoreTable($courseAnnualId);
        $columnName = $columnName->select('percentages.name', 'percentages.id as percentage_id')->groupBy('percentages.id')->orderBy('percentages.id')->get();
        $headers = $this->handsonTableHeaders($columnName);

        $columnHeader = $headers['colHeader'];
        $columns = $headers['column'];
        $colWidths = $headers['colWidth'];


        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId( $department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id);

        $allScoreByCourseAnnual = $this->studentScoreCourseAnnually($courseAnnual);
        $allNumberAbsences = $this->getAbsenceFromDB();


        if(count($department_option_ids)>0) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }

//        dd($arrayIdsOfDeptDegreeGradeDeptOption);
        if(count($groups)) {


            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.group', $groups)->get();
        } else {
            $studentByCourse = $studentByCourse->get();
        }

//        dd($studentByCourse);

        //----------------find student score if they have inserted

        $checkScoreReachHundredPercent=0;

        if($studentByCourse) {

            foreach($studentByCourse as $student) {
                $studentScore = isset($allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id])?$allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id]:[];
                $scoreAbsence = isset($allNumberAbsences[$courseAnnual->id][$student->student_annual_id])?$allNumberAbsences[$courseAnnual->id][$student->student_annual_id]:null;// get number of absence from database
                $totalScore = 0;
                $checkPercent=0;
                $scoreIds = []; // there are many score type for one subject and one student :example TP, Midterm, Final-exam
                if($studentScore) {
                    foreach($studentScore as $score) {
                        $checkPercent = $checkPercent +$score->percent; // we check the percentage if it is equal or bigger than 90 then we should now allow teacher to create more score
                        $totalScore = $totalScore + ($score->score);// calculate score for stuent annual
                        $scoreData[$score->name] = (($score->score != null)?$score->score: null);
                        $scoreData['percentage_id'.'_'.$score->name] =  $score->percentage_id;
                        $scoreData['score_id'.'_'.$score->name]=$score->score_id;
                        $scoreIds[] = $score->score_id;
                    }
                } else{
                    $scoreData=[];
                }

                //--calculate score absence to sum with the real score
                $totalCourseHours = ($courseAnnual->time_course + $courseAnnual->time_tp + $courseAnnual->time_td);
                $scoreAbsenceByCourse =  number_format((float)((($totalCourseHours)-(isset($scoreAbsence)?$scoreAbsence->num_absence:0))*10)/((($totalCourseHours != 0)?$totalCourseHours:1)), 2, '.', '');
                $totalScore = $totalScore + (($scoreAbsenceByCourse >= 0)?$scoreAbsenceByCourse:0);


                //----check if every student has the score equal or upper then 90 then we set status to true..then we will not allow teacher to add any score
                if($checkPercent >= 90 ) {
                    $checkScoreReachHundredPercent++;
                }

                /*------store average(a total score of one courseannual in table averages)-----------*/
                $input = [
                    'course_annual_id' => $courseAnnualId,
                    'student_annual_id' => $student->student_annual_id,
                    'average'   => $totalScore
                ];
                $storeTotalScore = $this->storeTotalScoreEachCourseAnnual($input, $scoreIds); // private function to store of update total score
                /*------------end of insert of update total score -------------*/

//            dd($storeTotalScore->description);
                // ------create element data array for handsontable
//            dd($scoreAbsenceByCourse);
                $element = array(
                    'student_annual_id'=>$student->student_annual_id,
                    'student_id_card' => $student->id_card,
                    'student_name' => $student->name_latin,
                    'student_gender' => $student->code,
                    'absence'          => (($scoreAbsenceByCourse >= 0)?$scoreAbsenceByCourse:10),
                    'num_absence'      => isset($scoreAbsence) ? $scoreAbsence->num_absence:null,
//                'average'          => isset($totalScore) ? (float)$totalScore->average: null,
                    'average'          => $totalScore,
                    'notation'        => $storeTotalScore->description,
//                'department_id'    => $courseAnnual->department_id,
//                'degree_id'        => $courseAnnual->degree_id,
//                'grade_id'         => $courseAnnual->grade_id,
//                'academic_year_id' => $cours/eAnnual->academic_year_id,
//                'semester_id'      => $courseAnnual->semester_id,
//                'employee_id'      => $courseAnnual->employee_id,
                );
                $mergerData = array_merge($element,$scoreData);
                $arrayData[] = $mergerData;
            }

            if($checkScoreReachHundredPercent == count($studentByCourse)) {
                return json_encode([
                    'status' => true,
                    'colWidths' => $colWidths,
                    'data' => $arrayData,
                    'columnHeader' => $columnHeader,
                    'columns'      =>$columns,
                    'should_add_score' => false
                ]);
            } else {
                return json_encode([
                    'status' => true,
                    'colWidths' => $colWidths,
                    'data' => $arrayData,
                    'columnHeader' => $columnHeader,
                    'columns'      =>$columns,
                    'should_add_score' => true
                ]);
            }
        } else {

            return Response::json(['status' => false, 'message'=> 'No Student Recod', 'course_properties' => $courseAnnual]);
        }


    }

    public function saveScoreByCourseAnnual(Request $request) {

        $inputs = $request->data;
        $checkUpdate = 0;
        $checkNotUpdated = 0;

        if($inputs) {
            foreach($inputs as $input) {

                if($input['score_id'] != null) {
                    $updateScore = $this->courseAnnualScores->update($input['score_id'], $input);

                    if($updateScore) {
                        $checkUpdate++;
                    }

                } else {
                    $checkNotUpdated++;
                }
            }
        }

        if($checkUpdate == count($inputs) - $checkNotUpdated) {

            $reDrawTable = $this->handsonTableData($inputs[0]['course_annual_id']);
            $reDrawTable =  json_decode($reDrawTable, true);

            return Response::json(['handsontableData' => $reDrawTable,'status'=>true, 'message' => 'Score Saved!!']);
        } else{

            return Response::json(['handsontableData' => [],'status'=>false, 'message' => 'Score NOt Saved!!']);
        }
    }


    private function getPropertiesFromScoreTable($courseAnnualId) {

//        dd($objectCourseAnnual);

//        $courseAnnualClass = $objectCourseAnnual->courseAnnualClass->first();// we get only the first course annual class because it has the same dept, grade, degree, dept option but different only group
        $course = DB::table('course_annuals')->where('id', $courseAnnualId)->first();


        $arrayIdsOf_Dept_Deg_Grd_DeptOp = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnualId);
        $department_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp['department_id'];
        $degree_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp['degree_id'];
        $grade_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp['grade_id'];

//        dd($department_ids);


        $tableScore = DB::table('scores')
            ->join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->whereIn('scores.department_id', $department_ids)
            ->whereIn('scores.degree_id', $degree_ids)
            ->whereIn('scores.grade_id', $grade_ids)
            ->where([
                ['scores.course_annual_id', $courseAnnualId],
                ['scores.semester_id', $course->semester_id],
                ['scores.academic_year_id', $course->academic_year_id]
            ]);


        return $tableScore;

    }

    private function studentScoreCourseAnnually($courseAnnual) {

        $arrayIdsOf_Dept_Grd_Deg_DeptOp = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnual->id);
        $department_ids = $arrayIdsOf_Dept_Grd_Deg_DeptOp['department_id'];
        $degree_ids = $arrayIdsOf_Dept_Grd_Deg_DeptOp['degree_id'];
        $grade_ids = $arrayIdsOf_Dept_Grd_Deg_DeptOp['grade_id'];
        $arrayData = [];
        $scores = DB::table('scores')
            ->join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->whereIn('scores.degree_id', $degree_ids)
            ->whereIn('scores.department_id', $department_ids)
            ->whereIn('scores.grade_id', $grade_ids)
            ->where([
                ['scores.course_annual_id', $courseAnnual->id],
                ['scores.semester_id', $courseAnnual->semester_id],
                ['scores.academic_year_id', $courseAnnual->academic_year_id]
            ])
            ->select(
                'scores.course_annual_id','scores.student_annual_id',
                'scores.score', 'scores.score_absence', 'percentages.name', 'percentages.percent', 'percentages.id as percentage_id', 'scores.id as score_id')
            ->get();

        foreach($scores as $score) {
            $arrayData[$courseAnnual->id][$score->student_annual_id][] = $score;
        }
        return ($arrayData);
    }

    private function getAbsenceFromDB() {

        $arrayData=[];

        $absences = DB::table('absences')->get();
        if($absences) {
            foreach($absences as $absence) {
                $arrayData[$absence->course_annual_id][$absence->student_annual_id] = $absence;
            }
        }
        return $arrayData;

    }

    private function getStudentByDeptIdGradeIdDegreeId($deptId, $degreeId, $gradeId, $academicYearID) {

        $studentAnnual = DB::table('studentAnnuals')
            ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
            ->join('genders', 'genders.id', '=', 'students.gender_id')
            ->whereIn('studentAnnuals.department_id', $deptId)
            ->whereIn('studentAnnuals.degree_id', $degreeId)
            ->whereIn('studentAnnuals.grade_id', $gradeId)
            ->where('studentAnnuals.academic_year_id', $academicYearID)
            ->select(
                'studentAnnuals.id as student_annual_id',
                'students.name_latin',
                'students.name_kh',
                'students.id_card',
                'genders.code',
                'studentAnnuals.group',
                'studentAnnuals.academic_year_id'
            )
            ->orderBy('students.id_card', 'ASC');
        return $studentAnnual;
    }


    private function createScorePercentage($midterm, $final, $courseAnnualId) {

        $check = 0;

        $courseAnnual = DB::table('course_annuals')->where('id', $courseAnnualId)->first();
        $percentageInput = [
            [
                'name'              =>   'Midterm-'.$midterm.'%',
                'percent'           => $midterm,
                'percentage_type'   => 'normal'
            ],
            [
                'name'              =>      'Final-'.$final.'%',
                'percent'           =>      $final,
                'percentage_type'   => 'normal'
            ]
        ];

        $arrayIdsOfDeptGradeDegreeDeptOption = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnualId);
        $department_ids = $arrayIdsOfDeptGradeDegreeDeptOption['department_id'];
        $degree_ids = $arrayIdsOfDeptGradeDegreeDeptOption['degree_id'];
        $grade_ids = $arrayIdsOfDeptGradeDegreeDeptOption['grade_id'];
        $department_option_ids = $arrayIdsOfDeptGradeDegreeDeptOption['department_option_id'];
        $groups = $arrayIdsOfDeptGradeDegreeDeptOption['group'];

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId( $department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id );

        if(count($department_option_ids)>0) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }
        if($groups) {
            $studentByCourse = $studentByCourse->whereIn('studentAnnuals.group', $groups)->get();
        } else {
            $studentByCourse =$studentByCourse->get();
        }

        foreach($percentageInput as $input) {

            $savePercentageId = $this->percentages->create($input);// return the percentage id

            if($studentByCourse) {
                foreach( $studentByCourse as $studentScore) {
                    $input = [
                        'course_annual_id'  =>  $courseAnnualId,
                        'student_annual_id' =>  $studentScore->student_annual_id,
                        'department_id'     =>  $courseAnnual->department_id,
                        'degree_id'         =>  $courseAnnual->degree_id,
                        'grade_id'          =>  $courseAnnual->grade_id,
                        'academic_year_id'  =>  $courseAnnual->academic_year_id,
                        'semester_id'       =>  $courseAnnual->semester_id,
                        'socre_absence'     =>  null
                    ];

                    $saveScoreId = $this->courseAnnualScores->create($input);// return the socreId
                    $savePercentageScore = $this->courseAnnualScores->createPercentageScore($saveScoreId->id, $savePercentageId->id);
                    if($savePercentageScore) {
                        $check++;
                    }
                }
            }
        }

        if($check == (count($studentByCourse) * count($percentageInput))) {
            return true;
        } else {
            return false;
        }

    }

    public function insertPercentageNameNPercentage(Request $request) {

    //this is to add new column name of the exam score ...and we have to initial the value 0 to the student for this type of exam

        $midterm = $request->percentage;
        $final = ScoreEnum::Midterm_Final - $midterm;
        $createScore = $this->createScorePercentage($midterm, $final,$request->course_annual_id );
        if($createScore) {
            $reDrawTable = $this->handsonTableData($request->course_annual_id);
            return $reDrawTable;
        }

    }

    public function storeNumberAbsence(Request $request) {

        $baseData = $request->baseData;
        $checkStore = 0;
        $checkUpdate=0;
        $checkNOTUpdatOrStore = 0;
        if(count($baseData) > 0) {

            $status =0;
            foreach($baseData as $data) {
                if(is_numeric($data['num_absence']) || $data['num_absence'] == null) {
                    $status++;
                }
            }


            if($status == count($baseData)) {
                foreach($baseData as $data) {

                    if($data['student_annual_id'] != null) {

                        $absence = $this->absences->findIfExist($data['course_annual_id'], $data['student_annual_id']);
                        if($absence) {
                            //update absence
                            $update = $this->absences->update($absence->id, $data);
                            if($update) {
                                $checkUpdate++;
                            }
                        } else {
                            // store absence
                            $store = $this->absences->create($data);
                            if($store) {
                                $checkStore++;
                            }
                        }
                    } else {
                        $checkNOTUpdatOrStore++;
                    }
                }
            } else {
                $reDrawTable = $this->handsonTableData($data['course_annual_id']);
                $reDrawTable = json_decode($reDrawTable);
                return Response::json(['status' => false, 'message' => 'There are null or String Value in cell!', 'handsonData'=> $reDrawTable]);
            }

        }
        if($checkStore+$checkUpdate == count($baseData)- $checkNOTUpdatOrStore) {
            $reDrawTable = $this->handsonTableData($data['course_annual_id']);
            $reDrawTable = json_decode($reDrawTable);
            return Response::json(['status' => true, 'message' => 'Stored!', 'handsonData'=> $reDrawTable]);
        }
    }

    public function deleteScoreFromScorePercentage(Request $request) {

//        dd($request->all());

        $status = 0;

        $scores =Score::join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->where('course_annual_id', $request->course_annual_id)
            ->select('scores.id as score_id', 'percentages.id as percentage_id')
            ->get();


        $arrayPercentageId=[];
        foreach($scores as $score){
            $arrayPercentageId[$score->percentage_id]=$score->percentage_id;
        }

        foreach($arrayPercentageId as $id) {
            $deletePercentage= $this->percentages->destroy($id);
            if($deletePercentage) {
                $status++;
            }
        }

        $deleteScore = DB::table('scores')->where('course_annual_id', $request->course_annual_id)->delete();

//        dd($deleteScore .'=='. count($scores));
        if($deleteScore) {
            $reDrawTable = $this->handsonTableData($request->course_annual_id);
            return $reDrawTable;
        }
    }


    public function calculateAverageByCourseAnnual($courseAnnualId, Request $request) {

        $colHeaders = $request->colHeader;
        $dataArray  = $request->data;

        dd($dataArray);
        $studentScores = [];
        $check =0;
        $count = 0;
        foreach($dataArray as $data){

            $count++;

            if($count < count($dataArray)) {
                $totalScore = 0;// this is the total score by only one course
                $scoreId = [];

                for($index=0; $index< count($colHeaders); $index++) {

                    if($index > 4  && $index < (count($colHeaders)-1)) { // we know the exact column header of the score so this we need only the score which every teacher created

                        $scoreHeader = explode('-', $colHeaders[$index]);
                        $percentage = (int)$scoreHeader[count($scoreHeader)-1];// convert string X% to integer X
                        $score = $data[$colHeaders[$index]];
                        $percentage_id = $data['percentage_id_'.$colHeaders[$index]];
                        $totalScore = $totalScore + (($score*$percentage)/100);

                        $scoreId[] = $data['score_id_'.$colHeaders[$index]];
                    }

                }

                // store average score in table average and relation table average_score
                $input = [
                    'course_annual_id' => $courseAnnualId,
                    'student_annual_id'=> $data['student_annual_id'],
                    'average'   => $totalScore
                ];



                $totalScore = $this->averages->findAverageByCourseIdAndStudentId($courseAnnualId, (int)$data['student_annual_id']);

                if($totalScore) {
                    //update calcuation total score
                    $UpdateAverage = $this->averages->update($totalScore->id, $input);

                    if($UpdateAverage) {
                        $check++;
                    }
                } else {

                    // insert new calculation score
                    $storeAverage = $this->averages->create($input);
                    if($storeAverage) {
                        // this is to store the relation table
                        $checkRelation =0;
                        foreach($scoreId as $score_id) {
                            $storeRelationTable = $this->averages->storeTableRelation($storeAverage->id, $score_id);

                            if($storeRelationTable) {
                                $checkRelation++;
                            }
                        }

                        if($checkRelation == count($scoreId) ) {
                            $check++;
                        }
                    }
                }

                $studentScores[$data['student_annual_id']][] = $totalScore;// if we wan to get array of total scores in one subject
            }
        }

        if($check == count($dataArray)-1) {

            $reDrawTable = $this->handsonTableData($courseAnnualId);
            return $reDrawTable;
        } else {
            return 'check is not enouht';
        }

//        dd($studentScores);
    }


    private function storeTotalScoreEachCourseAnnual($input,$scoreIds) {

        $totalScore = $this->averages->findAverageByCourseIdAndStudentId($input['course_annual_id'], (int)$input['student_annual_id']);// check if total score existe
        $check=false;
        if($totalScore) {
            //update calcuation total score
            $UpdateAverage = $this->averages->update($totalScore->id, $input);
            if($UpdateAverage) {
                return $UpdateAverage;
            }

        } else {
            // insert new calculation score
            $storeAverage = $this->averages->create($input); // store total score then return collection-with ID
            if($storeAverage) {
                // this is to store the relation table
                $checkRelation =0;
                foreach($scoreIds as $score_id) {
                    $storeRelationTable = $this->averages->storeTableRelation($storeAverage->id, $score_id);
                    if($storeRelationTable) {
                        $checkRelation++;
                    }
                }

                if($checkRelation == count($scoreIds) ) {
                   return $storeAverage;
                }
            }
        }
    }

//    --------------all course annual score  ---------------


    public function formScoreAllCourseAnnual(Request $request) {


        $deptId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $academicYearID = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $deptOptionId = $request->dept_option_id;
        $groupName = $request->group_name;
        $allStudentGroups = $this->getStudentGroupFromDB();

        $coursePrograms = DB::table('courses');

        $courseAnnuals = $this->getCourseAnnually();

        if($deptId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.department_id', $deptId);
            $coursePrograms = $coursePrograms->where('department_id', $deptId);
            $courseAnnuals = $courseAnnuals->where('departments.id', '=',$deptId);
        }
        if($academicYearID) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.academic_year_id', $academicYearID);
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearID);
        }
        if($degreeId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.degree_id', $degreeId);
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.grade_id', $gradeId);
            $coursePrograms = $coursePrograms->where('grade_id', $gradeId);
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.grade_id', '=',$gradeId);
        }
        if($semesterId) {

            $coursePrograms = $coursePrograms->where('semester_id', $semesterId);
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.department_option_id', $deptOptionId);
//            dd($courseAnnuals->get());
            $coursePrograms = $coursePrograms->where('department_option_id', $deptOptionId);
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.department_option_id', '=',$deptOptionId);
        } else {
            $deptOptionId= null;
        }
        $courseAnnuals = $courseAnnuals->orderBy('semester_id')->get();
        $coursePrograms = $coursePrograms->orderBy('semester_id')->get();
        $allStudentGroups = $allStudentGroups->lists('group', 'group');


        if(auth()->user()->allow("view-all-score-in-all-department")){
            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $deptOptions = null;
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $deptOptions = $this->deptHasOption($department_id);
        }

        $department = Department::where('id', $deptId)->first();
        $degree = Degree::where('id', $degreeId)->first();
        $grade = Grade::where('id', $gradeId)->first();
        $academicYear = AcademicYear::where('id', $academicYearID)->first();

        $academicYears = AcademicYear::orderBy("id","desc")->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_en','id')->toArray();
        $grades = Grade::lists('name_en','id')->toArray();
        $semesters = Semester::orderBy('id')->lists('name_en', 'id')->toArray();

        return view('backend.course.courseAnnual.includes.form_all_score_courses_annual', compact('department',
            'degree', 'grade', 'academicYear', 'semesters', 'semesterId', 'courseAnnuals',
            'departments', 'academicYears', 'degrees', 'grades', 'deptOptions', 'deptOptionId', 'department_id', 'coursePrograms', 'groupName', 'allStudentGroups'
            ));

    }


    public function allHandsontableData(Request $request) {

        $deptId = $request->dept_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $academicYearID = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $deptOptionId = $request->dept_option_id;
        $groupName = $request->group_name;
        $arrayData = [];
        $arraySemester = [];
        $ranks=[];
        $finalCredit=0;

        $courseAnnuals = $this->getCourseAnnually();

        if($deptId) {
            $courseAnnuals = $courseAnnuals->where('departments.id', '=',$deptId);
        }
        if($academicYearID) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearID);
        }
        if($degreeId) {
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $courseAnnuals = $courseAnnuals->where('course_annual_classes.grade_id', '=',$gradeId);
        }
        if($semesterId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
//            dd($deptOptionId);
            $courseAnnuals = $courseAnnuals->where('course_annual_department_option_id.department_option_id', '=',$deptOptionId);
        }

//        dd($courseAnnuals->select('course_annuals.course_id', DB::raw('count(*) as course_id'))->groupBy('course_id')->get());

        $students = $this->getStudentByDeptIdGradeIdDegreeId($deptId, $degreeId, $gradeId, $academicYearID);
        if($groupName) {
            $students = $students->where('group', $groupName);
        }
        $students = $students->get();
        $courseAnnuals = $courseAnnuals->orderBy('semester_id')->get();// note restricted order by semester this is very important to make dynamic table course of each year [if change there would have bugs]
//        dd($courseAnnuals->groupBy('course_id')->toArray());

        $allProperties = $this->getCourseAnnualWithScore($courseAnnuals);
        $averages = $allProperties['averages'];
        $absences = $allProperties['absences'];
        $arrayCourseScore = $allProperties['arrayCourseScore'];

        if($semesterId) {
            $nestedHeaders =  [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label'=> 'Absents', 'colspan'=> 2]
                ],
                ['#','', '', '',
                    ['label'=> 'Total', 'colspan'=>1],
                ]
            ];
            $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'S_'.$semesterId, 'colspan'=>1]]);
            $colWidths=  [50, 80, 220, 50, 60, 55];
        } else {
            $semesters = Semester::orderBy('id')->get();
            $colWidths=  [50, 80, 220, 50, 60, 55, 55];
            $nestedHeaders =  [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label'=> 'Absents', 'colspan'=> 3]
                ],
                ['#','', '', '',
                    ['label'=> 'Total', 'colspan'=>1],
                ]
            ];
            if($semesters) {
                foreach($semesters as $semester) {
                    $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'S_'.$semester->id, 'colspan'=>1]]);
                    $arraySemester = $arraySemester + ['S_'.$semester->id => 0];
                }
            }
        }



        $creditInEachSemester =  [];
        $courseAnnualByCourseId =[];
        if($courseAnnuals) {

            foreach($courseAnnuals as $courseAnnual) {

                $creditInEachSemester[$courseAnnual->semester_id][] = $courseAnnual->course_credit;
                $nestedHeaders[0] = array_merge($nestedHeaders[0], [['label'=>'S'.$courseAnnual->semester_id.'_'.$courseAnnual->course_name, 'colspan'=>2]]);
                $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'Abs', 'colspan'=>1], ['label'=> $courseAnnual->course_credit, 'colspan'=>1]]);
                $colWidths[] = 55;
                $colWidths[] = 50;
                $courseAnnualByCourseId[$courseAnnual->course->name_fr] = $courseAnnual;
            }

//            foreach( $courseAnnualByCourseId as $key => $course) {
////                dd($course);
//                $creditInEachSemester[$course->semester_id][] = $course->course_credit;
//                $nestedHeaders[0] = array_merge($nestedHeaders[0], [['label'=>'S'.$course->semester_id.'_'.$course->course_name, 'colspan'=>2]]);
//                $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'Abs', 'colspan'=>1], ['label'=> $course->course_credit, 'colspan'=>1]]);
//                $colWidths[] = 55;
//                $colWidths[] = 50;
//            }
        }



        if($semesterId) {
            $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S'.$semesterId.'_Moyenne']);
            $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum(isset($creditInEachSemester[$semesterId])?$creditInEachSemester[$semesterId]:[0])]);
        } else {
            $semesters = Semester::orderBy('id')->get();
            if($semesters) {
                foreach($semesters as $semester) {
                    $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S'.$semester->id.'_Moyenne']);
                    $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum(isset($creditInEachSemester[$semester->id])?$creditInEachSemester[$semester->id]:[0])]);
                    $finalCredit= $finalCredit + array_sum(isset($creditInEachSemester[$semester->id])?$creditInEachSemester[$semester->id]:[0]);
                }
            }
        }

        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Moyenne']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Rank']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Redouble']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Observation']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Rattrapage']);
        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Passage']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [$finalCredit]);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $nestedHeaders[1] = array_merge($nestedHeaders[1], [' ']);
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 70;
        $colWidths[] = 70;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;
        $colWidths[] = 100;

//        dd($nestedHeaders);


        $index =0;
        $allCredit = 0;// the same as total credit
        $finalMoyennes =0;
        $allMoyenneScoreOfStudentBySemester=[];
        $allMoyenneYearly=[];

        if($students) {

            foreach($students as $student) {
                $index++;
                $moyenne = 0;
                $element = array(
                    'number' => ' '.$index,
                    'student_id_card' => $student->id_card,
                    'student_name' => $student->name_latin,
                    'student_gender' => $student->code,
                    'total' => 0,
                );
                if($semesterId) {
                    $count=0;
                    $totalCredit=0;// for only each one semester
                    $totalAbs = 0;
                    $element = $element +['S_'.$semesterId => 0];

                    if($courseAnnuals) {
//                        dd($courseAnnuals);
                        foreach($courseAnnuals as $courseAnnual) {
                            $count++;
                            $totalCredit = $totalCredit +$courseAnnual->course_credit;

//                            $scoreBycourse = $this->getScoreEachCourse($courseAnnual->course_annual_id, $student->student_annual_id);
                            $scoreBycourse = isset($averages[$courseAnnual->course_annual_id][$student->student_annual_id])?$averages[$courseAnnual->course_annual_id][$student->student_annual_id]->average:0;
//                            $numAbs = $this->getAbsenceFromDB($courseAnnual->course_annual_id, $student->student_annual_id);
                            $numAbs = isset($absences[$courseAnnual->course_annual_id][$student->student_annual_id])?$absences[$courseAnnual->course_annual_id][$student->student_annual_id]->num_absence:0;

                            $moyenne = $moyenne + ($scoreBycourse * $courseAnnual->course_credit);

//                            $totalAbs = $totalAbs + (($numAbs)?$numAbs->num_absence:0);

                            $totalAbs = $totalAbs + $numAbs;

                            $element = $element + ['Abs'.'_'.$courseAnnual->course_name.'_'.$count =>  $numAbs, 'Credit'.'_'.$courseAnnual->course_name.'_'.$count => $scoreBycourse];
                        }
                        $element = $element +['S'.$semesterId.'_Moyenne'=> number_format((float)($moyenne/$totalCredit), 2, '.', '')];

                        $allMoyenneScoreOfStudentBySemester[$semesterId][] = number_format((float)($moyenne/$totalCredit), 2, '.', '');// to get all moyenne of all student then to find max min and average


                    }


                    $element['total']= $totalAbs; // assign value for the total absence
                    $element['S_'.$semesterId]= $totalAbs;// assigne value to the s_1
                    $element = $element +['Moyenne'=>number_format((float)($moyenne/(($totalCredit > 0)?$totalCredit:1)), 2, '.', '')];
                    $allMoyenneYearly[] = number_format((float)($moyenne/(($totalCredit > 0)?$totalCredit:1)), 2, '.', '');// array of total average for each student

//                    $ranks[] = number_format((float)($moyenne/(($totalCredit > 0)?$totalCredit:1)), 2, '.', '');

                    $ranks[$student->id_card] = number_format((float)($moyenne/(($totalCredit >0)?$totalCredit:1)), 2, '.', '');


                } else {

                    // if the user did not select the semester .....so that mean we need to score for all semesters

//                    dd($courseAnnuals);
                    $semesters = Semester::orderBy('id')->get();
                    if($semesters) {

                        foreach($semesters as $semester) {
                            $element = $element +['S_'.$semester->id => 0];
                        }
                        // here we have to add all the semesters value  .... before every course
                        $totalAbseneces = 0;
                        $finalMoyennes =0;


                        foreach($semesters as $semester) {

                            if($courseAnnuals) {
                                $count=0;
                                $creditBySemester = 0;
                                $absBySemester = 0;

                                foreach($courseAnnuals as $courseAnnual) {
                                    $count++;
                                    if($semester->id == $courseAnnual->semester_id) {
                                        $allCredit = $allCredit+ $courseAnnual->course_credit;
                                        $creditBySemester = $creditBySemester + $courseAnnual->course_credit;
//                                        $scoreBycourse = $this->getScoreEachCourse($courseAnnual->course_annual_id, $student->student_annual_id);
                                        $scoreBycourse = isset($averages[$courseAnnual->course_annual_id][$student->student_annual_id])?$averages[$courseAnnual->course_annual_id][$student->student_annual_id]->average:0;
//                                        $numAbs = $this->getAbsenceFromDB($courseAnnual->course_annual_id, $student->student_annual_id);
                                        $numAbs = isset($absences[$courseAnnual->course_annual_id][$student->student_annual_id])?$absences[$courseAnnual->course_annual_id][$student->student_annual_id]->num_absence:0;
                                        $absBySemester = $absBySemester + $numAbs;
                                        $moyenne = $moyenne + ($scoreBycourse * $courseAnnual->course_credit);

                                        $element = $element + ['Abs'.'_'.$courseAnnual->course_name.'_'.$count => $numAbs, 'Credit'.'_'.$courseAnnual->course_name.'_'.$count => $scoreBycourse];
                                    }
                                }

                                // --- here we add value to column moyenne by semester and assign value for each absence of the semester


                                $allMoyenneScoreOfStudentBySemester[$semester->id][] = number_format((float)($moyenne/(($creditBySemester > 0)?$creditBySemester:1)), 2, '.', '');// push all moyenne of all student by semester id
                                $element['S_'.$semester->id]= $absBySemester;
                                $totalAbseneces = $totalAbseneces + $absBySemester;
                                $semesterMoyenne[$semester->id]= number_format((float)($moyenne/(($creditBySemester > 0)?$creditBySemester:1)), 2, '.', '');
                                $finalMoyennes = $finalMoyennes + ($moyenne);
                            }

                        }

                        foreach($semesters as $semester) {
                            $element = $element +['S'.$semester->id.'_Moyenne'=> isset($semesterMoyenne[$semester->id])?$semesterMoyenne[$semester->id]:0];
                        }
                    }

                    $element['total']= $totalAbseneces;
                    $element = $element +['Moyenne'=>number_format((float)($finalMoyennes/(($allCredit >0)?$allCredit:1)), 2, '.', '')];
                    $allMoyenneYearly[] = number_format((float)($finalMoyennes/(($allCredit >0)?$allCredit:1)), 2, '.', '');// array of total average for each student
                    $ranks[$student->id_card] = number_format((float)($finalMoyennes/(($allCredit >0)?$allCredit:1)), 2, '.', '');
                }

                $element = $element +['Rank'=>0];
                $element = $element +['Redouble'=>' '];
                $element = $element +['Observation'=> ' '];
                $element = $element +['Rattrapage'=>' '];
                $element = $element +['Passage'=>' '];
                $element = $element +[' '=>' '];

                $arrayData[] = $element;
            }


//            dd($ranks);
//            asort($ranks);
//            $ranks = array_reverse($ranks);
//            $finalData=[];
//            $index =0;
//            foreach($ranks as $key => $rank) {
//                foreach($arrayData as $data) {
//                    if($data['student_id_card'] == $key ) {
//                        $data['Rank'] = $index+1;
//                        $data['number'] = $index+1;
//                        $finalData[] = $data;
//                        $index++;
//                    }
//                }
//            }


//            $arrayData = $finalData;

//            dd($finalData);
        }

        //-----here is used to add more about average max and min value ----

        $dataEmpty = ['','','','','']; // use to make one more row space ..

        $maxArray =[
            'number' =>'',
            'student_id_card' =>'',
            'student_name' => 'MAX',
            'student_gender' => '',
            'total' => '',
        ];
        $minArray = [
            'number' =>'',
            'student_id_card' =>'',
            'student_name' => 'MIN',
            'student_gender' => '',
            'total' => '',
        ];
        $averageArray=[
            'number' =>'',
            'student_id_card' =>'',
            'student_name' => 'MOYENNE',
            'student_gender' => '',
            'total' => '',
        ];

        $semesterMaxMoyen =[];
        $semesterMinMoyen =[];
        $semesterAverageMoyen =[];
        if($semesterId) {
            $dataEmpty= $dataEmpty + [''];
            $maxArray = $maxArray +['S_'.$semesterId => ''];
            $minArray = $minArray +['S_'.$semesterId => ''];
            $averageArray = $averageArray +['S_'.$semesterId => ''];

            $semesterMaxMoyen = $semesterMaxMoyen +  ['S'.$semesterId.'_Moyenne'=> max(isset($allMoyenneScoreOfStudentBySemester[$semesterId])?$allMoyenneScoreOfStudentBySemester[$semesterId]:[0])];
            $semesterMinMoyen = $semesterMinMoyen + ['S'.$semesterId.'_Moyenne'=> min(isset($allMoyenneScoreOfStudentBySemester[$semesterId])?$allMoyenneScoreOfStudentBySemester[$semesterId]:[0])];
            $semesterAverageMoyen =$semesterAverageMoyen + ['S'.$semesterId.'_Moyenne'=> number_format((float)((array_sum(isset($allMoyenneScoreOfStudentBySemester[$semesterId])?$allMoyenneScoreOfStudentBySemester[$semesterId]:[0])/count(isset($allMoyenneScoreOfStudentBySemester[$semesterId])?$allMoyenneScoreOfStudentBySemester[$semesterId]:[0]))), 2,'.', '')];
        } else {
            if($semesters) {

                foreach($semesters as $semester) {
                    $dataEmpty[] = '';
                    $maxArray = $maxArray +['S_'.$semester->id => ''];
                    $minArray = $minArray +['S_'.$semester->id => ''];
                    $averageArray = $averageArray +['S_'.$semester->id => ''];

                    $semesterMaxMoyen = $semesterMaxMoyen +  ['S'.$semester->id.'_Moyenne'=> max(isset($allMoyenneScoreOfStudentBySemester[$semester->id])?$allMoyenneScoreOfStudentBySemester[$semester->id]:[0])];
                    $semesterMinMoyen = $semesterMinMoyen + ['S'.$semester->id.'_Moyenne'=> min(isset($allMoyenneScoreOfStudentBySemester[$semester->id])?$allMoyenneScoreOfStudentBySemester[$semester->id]:[0])];
                    $semesterAverageMoyen =$semesterAverageMoyen + ['S'.$semester->id.'_Moyenne'=> number_format((float)((array_sum(isset($allMoyenneScoreOfStudentBySemester[$semester->id])?$allMoyenneScoreOfStudentBySemester[$semester->id]:[0])/count(isset($allMoyenneScoreOfStudentBySemester[$semester->id])?$allMoyenneScoreOfStudentBySemester[$semester->id]:[1]))), 2,'.','')];

                }
            }
        }

        $keyIndex=0;

        foreach($courseAnnuals as $courseAnnual) {
            $dataEmpty[] = '';
            $dataEmpty[] = '';
            $keyIndex++;
//            $eachMoyen = number_format((float)((array_sum()/count(isset($arrayCourseScore[$courseAnnual->course_annual_id])?$arrayCourseScore[$courseAnnual->course_annual_id]:[0]))), 2, '.', '');
            $maxArray = $maxArray + ['Abs'.'_'.$courseAnnual->course_name.'_'.$keyIndex => '', 'Credit'.'_'.$courseAnnual->course_name.'_'.$keyIndex => max(isset($arrayCourseScore[$courseAnnual->course_annual_id])?$arrayCourseScore[$courseAnnual->course_annual_id]:[0])];
            $minArray = $minArray + ['Abs'.'_'.$courseAnnual->course_name.'_'.$keyIndex => '', 'Credit'.'_'.$courseAnnual->course_name.'_'.$keyIndex => min(isset($arrayCourseScore[$courseAnnual->course_annual_id])?$arrayCourseScore[$courseAnnual->course_annual_id]:[0])];
            $averageArray = $averageArray + ['Abs'.'_'.$courseAnnual->course_name.'_'.$keyIndex => '', 'Credit'.'_'.$courseAnnual->course_name.'_'.$keyIndex =>number_format( (float)((array_sum(isset($arrayCourseScore[$courseAnnual->course_annual_id])?$arrayCourseScore[$courseAnnual->course_annual_id]:[0])/count(isset($arrayCourseScore[$courseAnnual->course_annual_id])?$arrayCourseScore[$courseAnnual->course_annual_id]:[0]))), 2, '.', '') ];//
        }

        $maxArray = $maxArray+ $semesterMaxMoyen;
        $minArray = $minArray+ $semesterMinMoyen;
        $averageArray = $averageArray+ $semesterAverageMoyen;

        $maxArray = $maxArray+ ['Moyenne'=>max(($allMoyenneYearly)?$allMoyenneYearly:[0])];
        $minArray = $minArray+ ['Moyenne'=>min(($allMoyenneYearly)?$allMoyenneYearly:[0])];
        $averageArray = $averageArray+ ['Moyenne'=>number_format((float)((array_sum(($allMoyenneYearly)?$allMoyenneYearly:[0])/count(($allMoyenneYearly)?$allMoyenneYearly:[0]))), 2, '.', '')];

        $arrayData[] = $dataEmpty; //
        $arrayData[] = $averageArray;
        $arrayData[] = $maxArray;
        $arrayData[] = $minArray;

        return json_encode([
            'data' => $arrayData,
//            'columnHeader' => $columnHeader,
            'nestedHeaders' => $nestedHeaders,
//            'columns'      =>$columns
            'colWidths' => $colWidths
        ]);

    }

    private function isArraysInterSected ($array_1, $array_2) {
        $check=0;
       if(count($array_1) == count($array_2)) {
           for($index=0; $index < count($array_1); $index++) {
               if($array_1[$index] == $array_2[$index]) {
                   return true;
               }
           }
           return false;
       } else {
           return false;
       }
    }

    private function manageStudentAnnual($arrayCourseAnnual) {

        $students = $this->studentAnnualFromDB();
        $sakMerlArray = [];
        foreach($arrayCourseAnnual as $course) {

            $sakMerlArray[] = ['course' =>$course, 'student'=>$students[$course->department_id][$course->degree_id][$course->grade_id][$course->academic_year_id][$course->group]];
        }
        return $sakMerlArray;
    }

    private function studentAnnualFromDB() {

        $arrayStudentAnnual=[];
        $students = DB::table('studentAnnuals')->get();

        foreach($students as $student) {
            $arrayStudentAnnual[$student->department_id][$student->degree_id][$student->grade_id][$student->academic_year_id][$student->group][]= $student->id;
        }

        return ($arrayStudentAnnual);
    }

    private function getCourseAnnualWithScore($courseAnnually) {// ---$courseAnnually---collections of all courses by dept, grade, semester ...

        $averageProps = $this->averagePropertiesFromDB();;
        $averageScore =  $averageProps['average_score'];
        $averageObject = $averageProps['average_object'];
        $absences = $this->absencePropFromDB();
        return ['averages'=>$averageObject,'absences'=>$absences, 'arrayCourseScore'=>$averageScore] ;
    }
    private function averagePropertiesFromDB() {
        $arrayAverage = [];
        $arrayScores=[];
        $averageProperties = DB::table('averages')
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
    private function absencePropFromDB() {

        $arrayAbsence=[];
        $absenceProperties = DB::table('absences')->get();
        foreach($absenceProperties as $absence) {
            $arrayAbsence[$absence->course_annual_id][$absence->student_annual_id]= $absence;
        }

        return $arrayAbsence;
    }

    public function switchCourseAnnual(Request $request) {

//        dd($request->all());

       return  $this->handsonTableData($request->course_annual_id);
    }

    public function saveEachCellNotationCourseAnnual (Request $request) {

        $input = [
            'course_annual_id' => $request->course_annual_id,
            'student_annual_id' => $request->student_annual_id,
            'description' => $request->description
        ];
        $find = $this->averages->findAverageByCourseIdAndStudentId($input['course_annual_id'], $input['student_annual_id']);
        if($find) {
            $update = $this->averages->update($find->id, $input);

            if($update) {
                return Response::json(['status'=>true]);
            }

        } else {
            $storeDescription = $this->averages->create($input);
            if($storeDescription) {
                return Response::json(['status'=>true]);
            }
        }
    }

    public function exportCourseScore(Request $request) {

        $studentListScore=[];
        $colHeaders =explode(',',  $request->col_headers);
        $courseAnnual = $this->courseAnnuals->findOrThrowException($request->course_annual_id);
        $allScoreByCourseAnnual = $this->studentScoreCourseAnnually($courseAnnual);


        $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup = $this->arrayIdsOfDeptGradeDegreeDeptOption($request->course_annual_id);
        $groups = $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup['group'];
        $department_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup['department_id'];
        $degree_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup['degree_id'];
        $grade_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup['grade_id'];
        $department_option_ids = $arrayIdsOf_Dept_Deg_Grd_DeptOp_Grooup['department_option_id'];

        $allNumberAbsences = $this->getAbsenceFromDB();
        $studentNotations = $this->getStudentNotation($request->course_annual_id);
        $students = $this->getStudentByDeptIdGradeIdDegreeId($department_ids, $degree_ids, $grade_ids,$courseAnnual->academic_year_id);


        if(count($department_option_ids) > 0) {
            $students = $students->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }
        if($groups) {
            $students = $students->whereIn('studentAnnuals.group', $groups)->get();
        } else {
            $students= $students->get();
        }

        foreach($students as $student ) {

            $studentScores = isset($allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id])?$allScoreByCourseAnnual[$courseAnnual->id][$student->student_annual_id]:[];
            $scoreAbsence = isset($allNumberAbsences[$courseAnnual->id][$student->student_annual_id])?$allNumberAbsences[$courseAnnual->id][$student->student_annual_id]:null;// get number of absence from database
            $totalScore = 0;
            if($studentScores) {
                foreach($studentScores as $score) {
                    $totalScore = $totalScore + ($score->score);// calculate score for stuent annual
                    $scoreData[$score->name] = (($score->score != null)?$score->score: null);
                }
            } else{
                $scoreData=[];
            }

            //--calculate score absence to sum with the real score
            $totalCourseHours = ($courseAnnual->time_course + $courseAnnual->time_tp + $courseAnnual->time_td);
            $scoreAbsenceByCourse =  number_format((float)((($totalCourseHours)-(isset($scoreAbsence)?$scoreAbsence->num_absence:0))*10)/((($totalCourseHours != 0)?$totalCourseHours:1)), 2, '.', '');
            $totalScore = $totalScore + (($scoreAbsenceByCourse >= 0)?$scoreAbsenceByCourse:0);

                    $element =[
                        "Student ID" => $student->id_card,
                        "Student Name" => $student->name_latin,
                        "M/F"           => $student->code,
                        "Abs"           => ($scoreAbsence)?$scoreAbsence->num_absence:0,
                        "Abs-10%"       => $scoreAbsenceByCourse,
                    ];
            $element = $element + $scoreData+ ["Total" =>$totalScore, "Notation" => isset($studentNotations[$student->student_annual_id])?$studentNotations[$student->student_annual_id]->description:''];
            $studentListScore[] = $element;
        }


        $courseName = explode(" ", $courseAnnual->name_en);
        $acronym = "";

        foreach ($courseName as $char) {
            $acronym .= $char[0];
        }
        $title = 'Student_Score_'.$acronym;
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }

//        dd($colHeaders);

        Excel::create($title, function($excel) use ($studentListScore, $title,$alpha,$colHeaders) {
            $excel->sheet($title, function($sheet) use($studentListScore,$title,$alpha,$colHeaders) {
                $sheet->fromArray($studentListScore);
            });
        })->download('xls');

    }

    public function formImportScore(Request $request) {
//        dd($request->all());
        $courseAnnual = $this->courseAnnuals->findOrThrowException($request->course_annual_id);
        return view('backend.course.courseAnnual.includes.popup_import_score_file', compact('courseAnnual'));
    }


    public static $isError=false;
    public static  $isNotAceptedScore=false;
    public static $ifScoreImported = 0;
    public static $ifAbsenceUpdated = 0;
    public static $ifAbsenceCreated =0;
    public static $countStudentScoreType = 1;
    public static $arrayMissedStudent = [];
    public static $isFileHasColumnScoreType = [];
    public static $isCellValueNull = false;
    public static $errorNumberAbsence = false;
    public static $isStringAllowed = false;
    public static $headerPercentage =0;
    public static $colHeader = '';


    public function importScore($courseAnnualId, Request $request) {
        //$now = Carbon::now()->format('Y_m_d_H');

        $courseAnnual = $this->getCourseAnnualById($courseAnnualId);

        if($request->file('import')!= null){
            $import = "score". '.' .$request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/course_annuals/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/course_annuals/'.$import;
            $students = $this->getStudentByNameAndIdCard($courseAnnualId);
            $absences = $this->getStudentAbsence($courseAnnualId);
            $notations = $this->getStudentNotation($courseAnnualId);

            DB::beginTransaction();
            try{
                Excel::filter('chunk')->load($storage_path)->chunk(150, function($results) use ($students, $courseAnnualId, $courseAnnual, $absences, $notations){

                    $firstrow = $results->first()->toArray();
                    if (isset($firstrow['student_id']) && isset($firstrow['student_name']) && array_key_exists("abs",$firstrow) && (count($firstrow)>7)) {
                        $results->each(function($row) use($students, $courseAnnualId, $courseAnnual, $absences, $notations)  {
                            $row = $row->toArray();
                            $scoreIds = $this->getScoreId($courseAnnualId);
                            if(isset($students[$row['student_id']])) {
                                $studentScoreIds = $scoreIds[$students[$row['student_id']]->student_annual_id];
                            } else {
                                $studentScoreIds=[];
                            }
                            if(count($studentScoreIds) > 0) {
                                CourseAnnualController::$countStudentScoreType = count($studentScoreIds);
                                $percentage = $this->getPercentage();

                                foreach($studentScoreIds as $scoreId) {

                                    if(array_key_exists(strtolower($percentage[$scoreId]), $row)) { // check the array key of score name
                                        if( ($row[strtolower($percentage[$scoreId])] == null) || is_numeric($row[strtolower($percentage[$scoreId])]) ) {

                                            $explode = explode('_',strtolower($percentage[$scoreId]));
                                            $percent = $explode[count($explode)-1];
                                            if(  (((float)$row[strtolower($percentage[$scoreId])] <= (float)$percent) && ((float)$row[strtolower($percentage[$scoreId])] >= 0)) ) {
                                                $input = [
                                                    'score'=> $row[strtolower($percentage[$scoreId])]
                                                ];
                                                $score = $this->courseAnnualScores->update($scoreId, $input);

                                                if($score) {
                                                    CourseAnnualController::$ifScoreImported++;
                                                }
                                            } else {
                                                CourseAnnualController::$isNotAceptedScore = true;
                                                CourseAnnualController::$headerPercentage = $percent;
                                                CourseAnnualController::$colHeader = $percentage[$scoreId];
                                                DB::rollback();
                                                break;
                                            }

                                        } else {
                                            // score value is exactly the string so we must not accept it
                                            CourseAnnualController::$isStringAllowed = true;
                                        }
                                    } else {
                                        CourseAnnualController::$isFileHasColumnScoreType[$percentage[$scoreId]]= $percentage[$scoreId];
                                    }
                                }

                                if(is_numeric($row['abs']) || ($row['abs'] == null)) { // ---absence column

                                    if( ( (float)($row['abs'] <= ($courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp)) && ((float)$row['abs'] >= 0)) ) {

                                        if(isset($absences[$students[$row['student_id']]->student_annual_id])) {

                                            $absence = $absences[$students[$row['student_id']]->student_annual_id];

                                            if($absence) {
                                                //----update student absence
                                                $input = [
                                                    'num_absence' => $row['abs']
                                                ];
                                                $update = $this->absences->update($absence->id, $input);
                                                if($update) {
                                                    CourseAnnualController::$ifAbsenceUpdated++;
                                                }
                                            }
                                        } else {

                                            //----create student absence
                                            $input = [
                                                'course_annual_id' => $courseAnnualId,
                                                'student_annual_id' => $students[$row['student_id']]->student_annual_id,
                                                'num_absence'       => $row['abs']
                                            ];
                                            $store = $this->absences->create($input);
                                            if($store) {
                                                CourseAnnualController::$ifAbsenceCreated++;
                                            }
                                        }
                                    } else {

                                        // the absence value is not in the conditioin
                                        CourseAnnualController::$errorNumberAbsence = true;
                                        DB::rollback();
                                    }

                                } else {
                                    // the absence value is exactly string
                                    CourseAnnualController::$errorNumberAbsence = true;
                                    DB::rollback();
                                }

                                //----------store notation-------------


                                if(isset($row['notation'])) { // ---notation column

                                    if(isset($notations[$students[$row['student_id']]->student_annual_id])) {

                                        $notation = $notations[$students[$row['student_id']]->student_annual_id];

                                        if($notation) {
                                            //----update student absence
                                            $input = [
                                                'course_annual_id' => $notation->course_annual_id,
                                                'student_annual_id' => $notation->student_annual_id,
                                                'description' => $row['notation']
                                            ];

                                            $update = $this->averages->update($notation->id, $input);
                                        }
                                    } else {
                                        //----create student absence
                                        $input = [
                                            'course_annual_id' => $courseAnnualId,
                                            'student_annual_id' => $students[$row['student_id']]->student_annual_id,
                                            'description'       => $row['notation']
                                        ];
                                        $store = $this->averages->create($input);
                                    }
                                }
                            } else {
                               CourseAnnualController::$arrayMissedStudent[] = $row;
                            }
                        });
                    } else {
                       CourseAnnualController::$isError = true;

                    }
                });

                if(CourseAnnualController::$isError) {
                    return redirect()->back()->with(['status'=>'Problem with no data in the first row, or your file misses some fields. To make file corrected please export the template!!']);
                }
                if(CourseAnnualController::$isNotAceptedScore) {
                    return redirect()->back()->with(['status' => CourseAnnualController::$colHeader.' score must be between 0 and '.CourseAnnualController::$headerPercentage.', no string allowed!']);
                }
                if(CourseAnnualController::$isStringAllowed) {
                    return redirect()->back()->with(['status' => 'No string allowed!']);
                }
                if(count(CourseAnnualController::$isFileHasColumnScoreType) > 0) {
                    $string = ' ';
                    foreach( CourseAnnualController::$isFileHasColumnScoreType as $scoreType) {
                        $string = $string .$scoreType. ' ';
                    }
                    return redirect()->back()->with(['status'=> 'Your file does not have this field score: '.$string.' Please export template as sample!']);
                }
//                if(CourseAnnualController::$isCellValueNull) {
//                    return redirect()->back()->with(['status'=> 'Cell Value Null is not allow. Please Add as 0']);
//                }
                if( CourseAnnualController::$errorNumberAbsence ) {
                    $string = 'The absence value must be between 0 and '.($courseAnnual->time_course + $courseAnnual->time_td + $courseAnnual->time_tp). ', and No string allowed!';
                    return redirect()->back()->with(['status'=> htmlspecialchars($string)]);
                }
            } catch(Exception $e){

                DB::rollback();
            }

            DB::commit();
            $dataSendToview = $this->dataSendToView($courseAnnualId);
            $courseAnnual = $dataSendToview['course_annual'];
            $availableCourses = $dataSendToview['available_course'];
            if(count(CourseAnnualController::$arrayMissedStudent) > 0) {
                $message ='Some student are missing!';
                $arrayMissedStudent = CourseAnnualController::$arrayMissedStudent;
//                return view('backend.course.courseAnnual.includes.form_input_score_course_annual', compact('courseAnnualId', 'courseAnnual', 'availableCourses', 'message', 'arrayMissedStudent'));
                return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status_student' => $arrayMissedStudent]);
            } else {

//                dd((CourseAnnualController::$ifScoreImported/CourseAnnualController::$countStudentScoreType) .'=='. count($students).'& '.(CourseAnnualController::$ifAbsenceUpdated + CourseAnnualController::$ifAbsenceCreated) .'=='. count($students));

                if( ((CourseAnnualController::$ifScoreImported/CourseAnnualController::$countStudentScoreType) == count($students)) && ( (CourseAnnualController::$ifAbsenceUpdated + CourseAnnualController::$ifAbsenceCreated) == count($students) ) ) {
                    $status = 'File Imported!';
//                    return view('backend.course.courseAnnual.includes.form_input_score_course_annual', compact('courseAnnualId', 'courseAnnual', 'availableCourses', 'status'));

                    return redirect(route('admin.course.form_input_score_course_annual', $courseAnnualId))->with(['status' => 'File Imported']);
                } else {
                    return redirect()->back()->with(['status'=> 'Something went wrong']);
                }
            }
        } else {
            return redirect()->back()->with(['status' => 'Please Select File!']);
        }
    }

    private function getStudentByNameAndIdCard($courseAnnualId) {


        $arrayIdsOf_Dept_Grd_Deg_Group = $this->arrayIdsOfDeptGradeDegreeDeptOption($courseAnnualId);
        $department_ids = $arrayIdsOf_Dept_Grd_Deg_Group['department_id'];
        $degree_ids = $arrayIdsOf_Dept_Grd_Deg_Group['degree_id'];
        $grade_ids = $arrayIdsOf_Dept_Grd_Deg_Group['grade_id'];
        $department_option_ids = $arrayIdsOf_Dept_Grd_Deg_Group['department_option_id'];
        $groups = $arrayIdsOf_Dept_Grd_Deg_Group['group'];

        $arrayStudent = [];
        $courseAnnual = $this->courseAnnuals->findOrThrowException($courseAnnualId);
        $students = $this->getStudentByDeptIdGradeIdDegreeId($department_ids, $degree_ids, $grade_ids, $courseAnnual->academic_year_id);

        if(count($department_option_ids)>0) {
            $students = $students->whereIn('studentAnnuals.department_option_id', $department_option_ids);
        }
        if($groups) {
            $students = $students->whereIn('studentAnnuals.group', $groups)->get();
        } else {
            $students = $students->get();
        }

        foreach($students as $student) {
            $arrayStudent[$student->id_card]=$student;
        }

        return $arrayStudent;
    }

    private function getScoreId($courseAnnualId) {

        $arrayScores = [];

        $scores = DB::table('scores')
            ->where([
                ['scores.course_annual_id', $courseAnnualId],
            ])->get();

        foreach($scores as $score) {
            $arrayScores[$score->student_annual_id][] = $score->id;
        }

        return $arrayScores;
    }

    private function getPercentage() {

        $arrayPercentage=[];
        $percentages = DB::table('percentages')
            ->join('percentage_scores','percentage_scores.percentage_id','=', 'percentages.id')
            ->join('scores', 'scores.id','=', 'percentage_scores.score_id')
            ->select('scores.id as score_id', 'percentages.percent', 'percentages.name')
            ->get();

        foreach($percentages as $percentage) {
            $trim =trim($percentage->name, '%');
            $strReplace = str_replace("-","_",$trim);
            $arrayPercentage[$percentage->score_id] = $strReplace;
        }

        return ($arrayPercentage);

    }

    private function getStudentAbsence($courseAnnualId) {

        $arrayAbsence =[];
        $absences = DB::table('absences')
            ->where('course_annual_id', $courseAnnualId)
            ->get();

        foreach($absences as $absence) {
            $arrayAbsence[$absence->student_annual_id] = $absence;
        }
        return $arrayAbsence;
    }

    private function getStudentNotation($courseAnnualId) {
        $arrayNotation =[];
        $notations = DB::table('averages')
            ->where('course_annual_id', $courseAnnualId)
            ->get();

        foreach($notations as $notation) {
            $arrayNotation[$notation->student_annual_id] = $notation;
        }

//        dd($arrayNotation);

        return $arrayNotation;

    }

    public function getGroupByCourseAnnual(Request $request) {


//        $courseAnnual = DB::table('course_annuals')->where('id', $request->course_annual_id)->first();
//
//        $allGroups = DB::table('course_annual_classes')->where([
//            ['course_annual_id', $courseAnnual->id],
//            ['course_session_id', null]
//        ]);
//
//        if(count($allGroups->get()) > 1) {
//
//            $allGroups = $allGroups->orderBy('group')->lists('group', 'group');
//        } else {
//            foreach($allGroups->get() as $group) {
//                if($group->group == null) {
//                    $allGroups = DB::table('studentAnnuals')->where([
//                        ['department_id', $courseAnnual->department_id],
//                        ['academic_year_id', $courseAnnual->academic_year_id],
//                        ['grade_id', $courseAnnual->grade_id],
//                        ['degree_id', $courseAnnual->degree_id],
//                    ])->orderBy('group')->lists('group', 'group');
//
//                    break;
//                }
//            }
//        }
//
//        asort($allGroups);
//        $groups = $course_session_groups[$request->course_annual_id];
//        if($groups) {
//            return view('backend.course.courseSession.group_by_course_session_selection', compact('groups'));
//        }

    }




}
