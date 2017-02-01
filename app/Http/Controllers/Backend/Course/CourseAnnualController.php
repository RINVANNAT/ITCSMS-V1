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
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Percentage;
use App\Models\StudentAnnual;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use App\Repositories\Backend\CourseAnnualScore\CourseAnnualScoreRepositoryContract;
use App\Repositories\Backend\Percentage\PercentageRepositoryContract;
use App\Repositories\Backend\Absence\AbsenceRepositoryContract;

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

    /**
     * @param CourseAnnualRepositoryContract $courseAnnualRepo
     */
    public function __construct(
        CourseAnnualRepositoryContract $courseAnnualRepo,
        CourseAnnualScoreRepositoryContract $courseAnnualScoreRepo,
        PercentageRepositoryContract $percentageRepo,
        AbsenceRepositoryContract $absenceRepo,
        AverageRepositoryContract $averageRepo
    )
    {
        $this->courseAnnuals = $courseAnnualRepo;
        $this->courseAnnualScores = $courseAnnualScoreRepo;
        $this->percentages = $percentageRepo;
        $this->absences = $absenceRepo;
        $this->averages = $averageRepo;
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
            $deptOptions = null;
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $deptOptions = $this->deptHasOption($department_id);
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

        return view('backend.course.courseAnnual.index',compact('departments','academicYears','degrees','grades', 'semesters', 'studentGroup','department_id','lecturers', 'deptOptions'));
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

    public function getDeptOption(Request $request) {

        $deptOptions = $this->deptHasOption($request->department_id);

        return view('backend.course.courseAnnual.includes.dept_option_selection', compact('deptOptions'));
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
            $deptOptions = null;
            $employees = Employee::orderBy('updated_at', 'desc')->lists("name_kh","id");
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $deptOptions = $this->deptHasOption($department_id);
            $courses = Course::where('courses.department_id', $department_id)->orderBy('updated_at', 'desc')->get();
            if(auth()->user()->allow("view-all-score-course-annual")){ // This is chef department, he can see all courses in his department
                $employees = Employee::where('employees.department_id', $department_id)->orderBy('updated_at', 'desc')->lists("name_kh","id");
            } else {
                $employees = null;
            }
        }
        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $semesters = Semester::lists("name_kh", "id");
        return view('backend.course.courseAnnual.create',compact('departments','academicYears','degrees','grades','courses',"semesters","employees", 'deptOptions'));
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
     * @param  StoreCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreCourseAnnualRequest $request)
    {


//        $groups = json_decode($request->group_selected);

//        if(count($groups)>0) {
//            foreach($groups as $group) {
//                $split = explode('_', $group);
//                if(count($split)>2) {
//
//                    dd($split);
//
//                    $input = [
//                        'group' => ''
//                    ];
//
//                }
//            }
//        }
        $data = $request->all();

        $this->courseAnnuals->create($data);
        
        return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.general.created'));
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

        $courseAnnual = $this->courseAnnuals->findOrThrowException($id);

        if(auth()->user()->allow("view-all-score-in-all-department")){

            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $courses = Course::orderBy('updated_at', 'desc')->get();
            $employees = Employee::orderBy('name_latin')->lists("name_kh","id");
            $deptOptions = null;

        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $deptOptions = $this->deptHasOption($department_id);
            $courses = Course::where('department_id', $department_id)->orderBy('updated_at', 'desc')->get();
            if(auth()->user()->allow("view-all-score-course-annual")){ // This is chef department, he can see all courses in his department
                $employees = Employee::where('department_id',$department_id)->lists("name_kh","id");
            } else {
                $employees = null;
            }
        }
        $academicYears = AcademicYear::lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $semesters = Semester::lists("name_kh", "id");
        return view('backend.course.courseAnnual.edit',compact('courseAnnual','departments','academicYears','degrees','grades','courses','employees','semesters', 'deptOptions'));
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
        $this->courseAnnuals->update($id, $request->all());
        return redirect()->route('admin.course.course_annual.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
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
        $employee =Employee::where('user_id', Auth::user()->id)->first();


        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->leftJoin('grades','course_annuals.grade_id', '=', 'grades.id')

            ->select([
                'course_annuals.id',
                'course_annuals.name_en as name',
                'course_annuals.semester_id',
                'course_annuals.active',
                'course_annuals.academic_year_id',
                'employees.name_latin as employee_id',
                'departments.code as department_id',
                'degrees.code as degree_id',
                'course_annuals.grade_id',
                'course_annuals.course_id',
                DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
            ])
            ->orderBy("course_annuals.degree_id","ASC")
            ->orderBy("course_annuals.department_id","ASC")
            ->orderBy("course_annuals.grade_id","ASC")
            ->orderBy("course_annuals.semester_id","ASC");


        $datatables =  app('datatables')->of($courseAnnuals);
        $datatables
            ->addColumn('action', function ($courseAnnual) {



            if(Auth::user()->allow('input-score-course-annual')) {
                return ' <a href="'.route('admin.course.form_input_score_course_annual',$courseAnnual->id).'" class="btn btn-xs btn-info input_score_course"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.'input score'.'"></i> Score </a>';

            } else {

                return  '<a href="'.route('admin.course.course_annual.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';

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
            $datatables->where('course_annuals.group', '=', $group);
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

        $courseAnnuals = CourseAnnual::join('courses', 'courses.id', '=', 'course_annuals.course_id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->select([
                'course_annuals.name_en as course_name',
                'course_annuals.id as course_annual_id',
                'course_annuals.course_id as course_id',
                'departments.id as department_id',
                'degrees.id as degree_id',
                'course_annuals.grade_id',
                'course_annuals.id as course_annual_id',
                'course_annuals.time_tp',
                'course_annuals.time_td',
                'course_annuals.time_course',
                'course_annuals.semester_id',
                'courses.code as code',
                'courses.credit as course_program_credit',
                'course_annuals.group',
                 'employees.id as employee_id',
                'course_annuals.active',
                'course_annuals.academic_year_id',
                'course_annuals.credit as course_annual_credit',
                'course_annuals.credit as course_credit'
            ]);

        return $courseAnnuals;
    }


    private function getCourseAnnualFromDB ($teacherId, $academicYearId, $grade_id, $degree_id) {

        $courseAnnuals = $this->getCourseAnnually();
        $courseAnnuals = $courseAnnuals->where('employees.id', $teacherId);
        $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', $academicYearId);
        if($degree_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }
        if($grade_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }
        $courseAnnuals = $courseAnnuals->get();
        return $courseAnnuals;

    }

    private function courseAnnualFromDB($academicYearId, $grade_id, $degree_id) {

        $arrayCourses = [];

        $courseAnnuals = $this->getCourseAnnually();
        $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', $academicYearId);
        if($degree_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }
        if($grade_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }
        $courseAnnuals = $courseAnnuals->get();

        foreach($courseAnnuals as $courseAnnual) {
            if($courseAnnual->employee_id != null) {
                $arrayCourses[$courseAnnual->employee_id][] = $courseAnnual;
            }

        }
        return $arrayCourses;
    }

    private function getNotSelectedCourseByDept($deptId, $academicYearId, $grade_id, $degree_id, $dept_option_id, $semester_id) {

        $courseAnnuals = $this->getCourseAnnually();

        $courseAnnuals = $courseAnnuals->where('course_annuals.employee_id', '=', null);// this to get not assigned courses

//        dd($courseAnnuals->get());
        if($deptId) {
            $courseAnnuals = $courseAnnuals->where('departments.id', '=',$deptId);
        }
        if($academicYearId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.academic_year_id', '=',$academicYearId);
        }


        if($degree_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }

        if($grade_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }

        if($dept_option_id != 'null') {
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$dept_option_id);
        }
        if($semester_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semester_id);
        }
        $courseAnnuals = $courseAnnuals->get();


        return $courseAnnuals;

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
        $courseByTeacher = $this->courseAnnualFromDB($academic_year_id, $grade_id, $degree_id);

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

                        $totalCoursePerSemester[$course->semester_id][]=$course->time_tp + $course->time_td + $course->time_course;

                        if($course->semester_id == SemesterEnum::SEMESTER_ONE) {

                            $timeTpS1 = $timeTpS1 +  $course->time_tp;
                            $timeTdS1 =$timeTdS1 + $course->time_td;
                            $timeCourseS1 = $timeCourseS1 + $course->time_course;

                        } else {
                            $timeTpS2 = $timeTpS2 +  $course->time_tp;
                            $timeTdS2 =$timeTdS2 + $course->time_td;
                            $timeCourseS2 = $timeCourseS2 + $course->time_course;
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

    public function getSeletedCourseByTeacherID(CourseAnnualAssignmentRequest $request) {

        $courses = [];
        $arayCourse =[];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $parent_id = $_GET['id'];
        $teacher_id = explode('_', $_GET['id'])[3];

        $courseByTeacher = $this->courseAnnualFromDB($academic_year_id, $grade_id, $degree_id );
        $selectedCourses = isset($courseByTeacher[$teacher_id])?$courseByTeacher[$teacher_id]:null;

        if($selectedCourses != null) {

            foreach($selectedCourses as $course) {

                $element = array(
                    "id" => $parent_id.'_courseannual_' . $course->course_annual_id,
                    "text" => $course->course_name.': Group '.$course->group. ' ('.$course->degree_id.$course->grade_id.')',
                    "children" => false,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false ],
                    "li_attr" => [
                        'class' => 'teacher_course',
                    ],
                );
                array_push($courses, $element);

            }

            $res = array_map("unserialize", array_unique(array_map("serialize", $courses)));

            foreach ($res as $a) {
                array_push($arayCourse, $a);
            }

            return Response::json($arayCourse);
        }

    }

    public function getAllCourseByDepartment (Request $request) {

        //CourseAnnualAssignmentRequest

        $deptId = explode('_', $_GET['id'])[1];
        $arrayCourses = [];
        $Course = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;
        $dept_option_id = $request->department_option_id;
        $semester_id = $request->semester_id;

//        dd($request->all());

        $notSelectedCourses = $this->getNotSelectedCourseByDept($deptId, $academic_year_id, $grade_id, $degree_id, $dept_option_id, $semester_id);


        if($notSelectedCourses) {

            foreach($notSelectedCourses as $course) {

                $totalCoursePerSemester = $course->time_tp + $course->time_td + $course->time_course;

                $element = array(
                    "id" => 'department_'.$deptId.'_'.'course_' . $course->course_annual_id,
                    "text" => $course->course_name.': Group '.$course->group.' (S_'.$course->semester_id.' = '.$totalCoursePerSemester.')',
                    "li_attr" => [
                        'class' => 'department_course',
                        'tp'    => $course->time_tp,
                        'td'    => $course->time_td,
                        'course' => $course->time_course,
                        'course_name' => $course->course_name
                    ],

                    'grade' => $course->grade_id,
                    "type" => "course",
                    "state" => ["opened" => false, "selected" => false ]

                );

                array_push($arrayCourses, $element);

            }

        }
        $res = array_map("unserialize", array_unique(array_map("serialize", $arrayCourses)));

        foreach ($res as $a) {
            array_push($Course, $a);
        }

        return Response::json($Course);

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

        dd($arrayGroup);
        return Response::json($arrayGroup);
    }

    public function courseAssignment (CourseAnnualAssignmentRequest $request) {

        $academicYear = AcademicYear::where('id', $request->academic_year_id)->first();
        $departmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $deptOption = $request->department_option_id;
        $semesterId = $request->semester_id;
        if($deptOption == '') {
            $deptOption = null;
        }
        return view('backend.course.courseAnnual.includes.popup_course_assignment', compact('academicYear', 'departmentId', 'gradeId', 'degreeId', 'deptOption', 'semesterId'));
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
                    $course_annual_id = $explode[5];
                    $update = $this->updateCourse($course_annual_id, $inputs = '');

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

    private function updateCourse($courseAnnualId, $input) {


        $courseAnnual = $this->courseAnnuals->findOrThrowException((int)$courseAnnualId);
        $courseAnnual->active = isset($input['active'])?true:false;
        $courseAnnual->employee_id = isset($input['employee_id'])?$input['employee_id']:null;
        $courseAnnual->write_uid = auth()->id();

        if ($courseAnnual->save()) {
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
                        $employee_id = $teacherId[3];

                        foreach($arrayCourseId as $course) {

                            $courseId = explode('_', $course);

                            if(count($courseId) == 4) {

                                $course_annual_id = $courseId[3];

                                $input = [
                                    'active' => true,
                                    'employee_id' => $employee_id,
                                ];
                                $res = $this->updateCourse($course_annual_id, $input);

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

        $explode = explode('_', $request->dept_course_id);
        $courseAnnualId = $explode[3];
        $deptId = $explode[1];
        $course = $this->getCourseAnnualById($courseAnnualId);
        $allSemesters = Semester::get();
        $allGroups = StudentAnnual::where([
            ['department_id', $deptId],
            ['academic_year_id', $course->academic_year_id]
        ])
            ->groupBy('group')->orderBy('group', 'ASC')->lists('group', 'group');

        if($course) {

            return view('backend.course.courseAnnual.includes.popup_edit_course_annual', compact('course', 'allSemesters', 'allGroups'));
        }

    }

    public function editCourseAnnual($courseId, CourseAnnualAssignmentRequest $request) {

        $preCourse = $this->getCourseAnnualById($courseId);

        $inputs = [
            'time_course'   => $request->time_course,
            'time_td'       => $request->time_td,
            'time_tp'       => $request->time_tp,
            'name_kh'       => $request->name_kh,
            'name_en'       => $request->name_en,
            'name_fr'       => $request->name_fr,
            'semester_id'   => $request->semester_id,
            'employee_id'   => $preCourse->employee_id,
            'course_id'     => $preCourse->course_id,
            'department_id' => $preCourse->department_id,
            'active'        => true,
            'group'         => $request->group,
            'credit'        => $request->course_annual_credit
        ];

        $update =  $this->courseAnnuals->update($courseId, $inputs);
        if($update) {
            return Response::json(['status'=>true, 'message'=>'update course successfully!']);
        } else {
            return Response::json(['status'=>false, 'message'=>'Error Updating!!']);
        }
    }

    public function douplicateCourseAnnual(CourseAnnualAssignmentRequest $request) {

//        dd($request->all());

        $explode = explode('_',$request->dept_course_id);
        $courseAnnualId = $explode[3];
        $courseAnnual = $this->getCourseAnnualById($courseAnnualId);
        $inputs = [
            'time_course'   => $courseAnnual->time_course,
            'time_td'       => $courseAnnual->time_td,
            'time_tp'       => $courseAnnual->time_tp,
            'name_kh'       => $courseAnnual->name_kh.'_(copy)',
            'name_en'       => $courseAnnual->name_en.'_(copy)',
            'name_fr'       => $courseAnnual->name_fr.'_(copy)',
            'course_id'     => $courseAnnual->course_id,
            'semester_id'   => $courseAnnual->semester_id,
            'active'        => true,
            'academic_year_id'      => $courseAnnual->academic_year_id,
            'department_id'         => $courseAnnual->department_id,
            'degree_id'             => $courseAnnual->degree_id,
            'grade_id'              => $courseAnnual->grade_id,
            'credit'                => $courseAnnual->credit

        ];

        $save =  $this->courseAnnuals->create($inputs);

        if($save) {
            return Response::json(['status'=>true, 'message'=>'Course Duplicated!']);
        } else {
            return Response::json(['status'=>false, 'message'=>'Error Duplicated!']);
        }

    }

    public function deleteCourseAnnual(CourseAnnualAssignmentRequest $request) {

        $explode = explode('_',$request->dept_course_id);
        $courseAnnualId = $explode[3];

        $delete = $this->courseAnnuals->destroy($courseAnnualId);

        if($delete) {
            return Response::json(['status'=>true, 'message'=>'Successfully Deleted!']);
        } else {
            return Response::json(['status'=>true, 'message'=>'Error Deleted!']);
        }

    }

    private function getCourseAnnualById ($courseAnnualId) {

        $course = CourseAnnual::where('id', $courseAnnualId)->first();

        return $course;

    }


    public function generateCourseAnnual(Request $request) {

        $courseAnnual= DB::table('course_annuals')->where('academic_year_id', $request->academic_year_id-1);
//        dd($request->all());
        $departmentId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $check =0;
        $unCheck=0;
        $countIsGenerated =0;

        if($departmentId) {
            $courseAnnual = $courseAnnual->where('course_annuals.department_id', '=', $departmentId);
        }
        if($degreeId) {
            $courseAnnual = $courseAnnual->where('course_annuals.degree_id', '=', $degreeId);
        }
        if($gradeId) {
            $courseAnnual = $courseAnnual->where('course_annuals.grade_id', '=', $gradeId);
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
            ->where([
                ['course_id', '=', $courseId],
                ['semester_id', '=', $semesterId],
                ['academic_year_id', '=', $academicYearId],
                ['department_id', '=', $departmentId],
                ['degree_id', '=', $degreeId],
                ['grade_id', '=', $gradeId],
                ['employee_id', '=', $employeeId]
            ])
            ->get();



        if($select) {
//            dd($select);
            return true;
        } else {
            return false;
        }

    }

    private function dataSendToView($courseAnnualId) {

        $courseAnnual = $this->getCourseAnnualById($courseAnnualId);
        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $availableCourses = CourseAnnual::where([
            ['department_id', $courseAnnual->department_id],
            ['academic_year_id', $courseAnnual->academic_year_id],
            ['semester_id', $courseAnnual->semester_id]
        ]);

        if(auth()->user()->allow("view-all-score-in-all-department") || auth()->user()->allow('view-all-score-course-annual')) {

            $availableCourses = $availableCourses->orderBy('id')->get();
        } else {
            if(auth()->user()->allow("input-score-course-annual")){ // only teacher in every department who have this permission
                $availableCourses = $availableCourses->where('employee_id', $employee->id)->orderBy('id')->get();
            } else {
                $availableCourses = $availableCourses->orderBy('id')->get();
            }
        }

        return [
            'course_annual' => $courseAnnual,
            'available_course'  =>$availableCourses
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

//        $courseAnnual = $this->getCourseAnnualById($request->course_annual_id);

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

    private function handsonTableData($courseAnnualId) {

        $arrayData = [];
        $courseAnnual = $this->getCourseAnnualById($courseAnnualId);
        $columnName = $this->getPropertiesFromScoreTable($courseAnnual);
        $columnName = $columnName->select('percentages.name', 'percentages.id as percentage_id')->groupBy('percentages.id')->orderBy('percentages.id')->get();
        $headers = $this->handsonTableHeaders($columnName);
        $columnHeader = $headers['colHeader'];
        $columns = $headers['column'];
        $colWidths = $headers['colWidth'];
        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId( $courseAnnual->department_id, $courseAnnual->degree_id, $courseAnnual->grade_id, $courseAnnual->academic_year_id);
        $allScoreByCourseAnnual = $this->studentScoreCourseAnnually($courseAnnual);
        $allNumberAbsences = $this->getAbsenceFromDB();

        if($courseAnnual->department_option_id) {
            $studentByCourse = $studentByCourse->where('studentAnnuals.department_option_id', $courseAnnual->department_option_id);
        }
        if($courseAnnual->group) {
            $studentByCourse = $studentByCourse->where('studentAnnuals.group', $courseAnnual->group)->get();
        } else {
            $studentByCourse = $studentByCourse->get();
        }

        //----------------find student score if they have inserted

        $checkScoreReachHundredPercent=0;
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
                'department_id'    => $courseAnnual->department_id,
                'degree_id'        => $courseAnnual->degree_id,
                'grade_id'         => $courseAnnual->grade_id,
                'academic_year_id' => $courseAnnual->academic_year_id,
                'semester_id'      => $courseAnnual->semester_id,
                'employee_id'      => $courseAnnual->employee_id,
            );
            $mergerData = array_merge($element,$scoreData);
            $arrayData[] = $mergerData;
        }

        if($checkScoreReachHundredPercent == count($studentByCourse)) {
            return json_encode([
                'colWidths' => $colWidths,
                'data' => $arrayData,
                'columnHeader' => $columnHeader,
                'columns'      =>$columns,
                'should_add_score' => false
            ]);
        } else {
            return json_encode([
                'colWidths' => $colWidths,
                'data' => $arrayData,
                'columnHeader' => $columnHeader,
                'columns'      =>$columns,
                'should_add_score' => true
            ]);
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


    private function getPropertiesFromScoreTable($objectCourseAnnual) {

//        dd($objectCourseAnnual);

        $tableScore = DB::table('scores')
            ->join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->where([
                ['scores.course_annual_id', $objectCourseAnnual->id],
                ['scores.degree_id', $objectCourseAnnual->degree_id],
                ['scores.grade_id', $objectCourseAnnual->grade_id],
                ['scores.semester_id', $objectCourseAnnual->semester_id],
                ['scores.department_id', $objectCourseAnnual->department_id],
                ['scores.academic_year_id', $objectCourseAnnual->academic_year_id]
            ]);

        return $tableScore;
    }

    private function studentScoreCourseAnnually($courseAnnual) {

        $arrayData = [];
        $scores = DB::table('scores')
            ->join('percentage_scores', 'percentage_scores.score_id', '=', 'scores.id')
            ->join('percentages', 'percentages.id', '=', 'percentage_scores.percentage_id')
            ->where([
                ['scores.course_annual_id', $courseAnnual->id],
                ['scores.degree_id', $courseAnnual->degree_id],
                ['scores.grade_id', $courseAnnual->grade_id],
                ['scores.semester_id', $courseAnnual->semester_id],
                ['scores.department_id', $courseAnnual->department_id],
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
            ->where([
                ['studentAnnuals.department_id', $deptId],
                ['studentAnnuals.degree_id', $degreeId],
                ['studentAnnuals.grade_id', $gradeId],
                ['studentAnnuals.academic_year_id', $academicYearID]
            ])
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

    public function insertPercentageNameNPercentage(Request $request) {

    //this is to add new column name of the exam score ...and we have to initial the value 0 to the student for this type of exam

        $check =0;
        $courseAnnual = $this->getCourseAnnualById($request->course_annual_id);
        if($request->percentage <= 90) {
            $percentageInput = [
                [
                    'name'              =>   $request->percentage_name,
                    'percent'           => $request->percentage,
                    'percentage_type'   => $request->percentage_type
                ],
                [
                    'name'              =>   'Final-'.(90-$request->percentage).'%',
                    'percent'           => 90 - $request->percentage,
                    'percentage_type'   => $request->percentage_type
                ]
            ];

        } else {
            return Response::json(['status' => false, 'message'=> 'Score percentage must not over than 90']);
        }

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId(
            $courseAnnual->department_id,
            $courseAnnual->degree_id,
            $courseAnnual->grade_id,
            $courseAnnual->academic_year_id
        );

        if($courseAnnual->group) {
            $studentByCourse = $studentByCourse->where('studentAnnuals.group', $courseAnnual->group)->get();
        } else {
            $studentByCourse =$studentByCourse->get();
        }

        foreach($percentageInput as $input) {

            $savePercentageId = $this->percentages->create($input);// return the percentage id
            if($studentByCourse) {
                foreach( $studentByCourse as $studentScore) {
                    $input = [
                        'course_annual_id'  =>  $request->course_annual_id,
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
        }
        $deleteScore = DB::table('scores')->where('course_annual_id', $request->course_annual_id)->delete();

        if($deleteScore == count($scores)) {
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
//        if($check == true) {
//            return $storeAverage;
//        } else {
//            return [];
//        }
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
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.grade_id', $gradeId);
            $coursePrograms = $coursePrograms->where('grade_id', $gradeId);
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$gradeId);
        }
        if($semesterId) {

            $coursePrograms = $coursePrograms->where('semester_id', $semesterId);
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
            $allStudentGroups = $allStudentGroups->where('studentAnnuals.department_option_id', $deptOptionId);
//            dd($courseAnnuals->get());
            $coursePrograms = $coursePrograms->where('department_option_id', $deptOptionId);
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$deptOptionId);
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
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degreeId);
        }
        if($gradeId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$gradeId);
        }
        if($semesterId) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.semester_id', '=',$semesterId);
        }
        if($deptOptionId) {
//            dd($deptOptionId);
            $courseAnnuals = $courseAnnuals->where('course_annuals.department_option_id', '=',$deptOptionId);
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
        $allNumberAbsences = $this->getAbsenceFromDB();
        $students = $this->getStudentByDeptIdGradeIdDegreeId($courseAnnual->department_id, $courseAnnual->degree_id, $courseAnnual->grade_id,$courseAnnual->academic_year_id);


        if($courseAnnual->department_option_id) {
            $students = $students->where('studentAnnuals.department_option_id', $courseAnnual->department_option_id);
        }
        if($courseAnnual->group) {
            $students = $students->where('studentAnnuals.group', $courseAnnual->group)->get();
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
            $element = $element + $scoreData+ ["Total" =>$totalScore, "Notation" =>'',];
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
                    return redirect()->back()->with(['warning' => 'Score must be between 0 and '.CourseAnnualController::$headerPercentage]);
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

        $arrayStudent = [];
        $courseAnnual = $this->courseAnnuals->findOrThrowException($courseAnnualId);
        $students = $this->getStudentByDeptIdGradeIdDegreeId($courseAnnual->department_id, $courseAnnual->degree_id, $courseAnnual->grade_id, $courseAnnual->academic_year_id);
        if($courseAnnual->department_option_id) {
            $students = $students->where('studentAnnuals.department_option_id', $courseAnnual->department_option_id);
        }
        if($courseAnnual->group) {
            $students = $students->where('studentAnnuals.group', $courseAnnual->group)->get();
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

        return $arrayNotation;

    }




}
