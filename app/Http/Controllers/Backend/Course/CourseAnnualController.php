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

        $departments = Department::orderBy("code")
            ->where("code","!=","Study Office")
            ->where("code","!=","Academic")
            ->where("code","!=","Finance")
            ->get();

        $departmentTmps = array();
        foreach ($departments as $value){
            array_push($departmentTmps,$value['code']." - ".$value["name_en"]);
        }
        $departments = $departmentTmps;


        $academicYears = AcademicYear::orderBy("id","desc")->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_en','id')->toArray();
        $grades = Grade::lists('name_en','id')->toArray();
        $courses = Course::orderBy("updated_at","desc")->lists('name_en','id')->toArray();
        $semesters = Semester::orderBy('id')->lists('name_en', 'id')->toArray();

        $employees = Employee::lists("name_latin","id")->toArray();
        return view('backend.course.courseAnnual.index',compact('departments','academicYears','degrees','grades','courses','employees', 'semesters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseAnnualRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseAnnualRequest $request)
    {
        $departments = Department::lists('name_en','id')->toArray();
        $academicYears = AcademicYear::orderBy('id', 'desc')->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
//        $courses = Course::orderBy('updated_at', 'desc')->lists('name_kh','id')->toArray();
        $courses = Course::orderBy('updated_at', 'desc')->get();

        $semesters = Semester::lists("name_kh", "id");
        $employees = Employee::orderBy('updated_at', 'desc')->lists("name_kh","id");
        return view('backend.course.courseAnnual.create',compact('departments','academicYears','degrees','grades','courses',"semesters","employees"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreCourseAnnualRequest $request)
    {

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
     * @param EditCourseProgramRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCourseAnnualRequest $request, $id)
    {
        $courseAnnual = $this->courseAnnuals->findOrThrowException($id);
        $departments = Department::lists('name_kh','id')->toArray();

        $academicYears = AcademicYear::lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
//        $courses = Course::lists('name_kh','id')->toArray();

        $courses = Course::orderBy('updated_at', 'desc')->get();

        $semesters = Semester::lists("name_kh", "id");
        $employees = Employee::lists("name_kh","id");
        return view('backend.course.courseAnnual.edit',compact('courseAnnual','departments','academicYears','degrees','grades','courses','employees','semesters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseProgramRequest  $request
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
     * @param  update_score_per  $request
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
     * @param DeleteCourseProgramRequest $request
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
        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
//            ->leftJoin('grades','course_annuals.grade_id', '=', 'grade.id')

            ->orderBy("course_annuals.updated_at","desc")
            ->select(
                ['course_annuals.id',
                    'courses.name_en as name',
                    'course_annuals.semester_id',
                    'course_annuals.active',
                    'course_annuals.academic_year_id',
                    'employees.name_latin as employee_id',
                    'departments.code as department_id',
                    'degrees.code as degree_id',
//                    'grades.code  as grade_id',
                    'courses.grade_id',
                    'course_annuals.course_id']
            );


        $datatables =  app('datatables')->of($courseAnnuals);
        $datatables
            ->editColumn('name', '{!! $name !!}')
            ->editColumn('semester_id', '{!! $semester_id !!}')
            ->editColumn('academic_year_id', '{!! $academic_year_id !!}')
            ->editColumn('department_id', '{!! $department_id !!}')
            ->editColumn('degree_id', '{!! $degree_id !!}')
            ->editColumn('grade_id', '{!! $grade_id !!}')
            ->editColumn('employee_id', '{!! $employee_id !!}')
            ->addColumn('action', function ($courseAnnual) {
                return  '<a href="'.route('admin.course.course_annual.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>'.
                ' <a href="'.route('admin.course.form_input_score_course_annual',$courseAnnual->id).'" class="btn btn-xs btn-info input_score_course"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.'input score'.'"></i> Input Score </a>';
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
        if ($department = $datatables->request->get('department')) {
            $datatables->where('course_annuals.department_id', '=', $department);
        }

        if ($lecturer = $datatables->request->get('lecturer')) {
            $datatables->where('course_annuals.employee_id', '=', $lecturer);
        }

        if ($semester = $datatables->request->get('semester')) {
            $datatables->where('course_annuals.semester_id', '=', $semester);
        }


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


    private function getCourseAnnualFromDB ($teacherId, $academicYearId, $grade_id, $degree_id) {

        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
//            ->leftJoin('grades','course_annuals.grade_id', '=', 'grade.id')

            ->orderBy("course_annuals.updated_at","desc")
            ->select(
                ['course_annuals.id',
                    'course_annuals.name_en as course_name',
                    'course_annuals.course_id as course_id',
                    'course_annuals.semester_id',
                    'course_annuals.active',
                    'course_annuals.academic_year_id',
                    'employees.name_latin as employee_id',
                    'departments.code as department_id',
                    'degrees.code as degree_id',
                    'course_annuals.grade_id',
                    'course_annuals.course_id',
                    'course_annuals.time_tp',
                    'course_annuals.time_td',
                    'course_annuals.time_course',

                ]
            )
            ->where([
                ['employees.id', $teacherId],
                ['course_annuals.academic_year_id', $academicYearId]
            ]);

        if($degree_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.degree_id', '=',$degree_id);
        }
        if($grade_id) {
            $courseAnnuals = $courseAnnuals->where('course_annuals.grade_id', '=',$grade_id);
        }
        $courseAnnuals = $courseAnnuals->get();
        return $courseAnnuals;

    }


    private function getCourseAnnually() {

        $courseAnnuals = DB::table('course_annuals')
            ->join('courses', 'courses.id', '=', 'course_annuals.course_id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->select([
                'course_annuals.name_en as course_name',
                'course_annuals.id as course_annual_id',
                'course_annuals.course_id as course_id',
                'departments.code as department_id',
                'degrees.code as degree_id',
                'course_annuals.grade_id',
                'course_annuals.id as course_annual_id',
                'course_annuals.time_tp',
                'course_annuals.time_td',
                'course_annuals.time_course',
                'course_annuals.semester_id',
                'courses.code as code',
                'courses.credit as course_credit'
            ]);

        return $courseAnnuals;
    }

    private function getNotSelectedCourseByDept($deptId, $academicYearId, $grade_id, $degree_id) {


        $courseAnnuals = $this->getCourseAnnually();

        $courseAnnuals = $courseAnnuals->where('course_annuals.employee_id', '=', null);// this to get not assigned courses

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
        $courseAnnuals = $courseAnnuals->get();
        return $courseAnnuals;

    }

    private function getAllteacherByDeptId ($deptID) {

        $allTeachers = DB::table('employees')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->select('employees.name_kh as teacher_name', 'employees.id as teacher_id', 'departments.id as department_id')
            ->where('departments.id', $deptID)
            ->distinct('BINARY employees.name_kh')
            ->get();

        return $allTeachers;

    }

    public function getAllDepartments(CourseAnnualAssignmentRequest $request) {

        $allDepartments= [];

        $deparmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;
        $academicId = $request->academic_year_id;

        $depts = DB::table('departments')
            ->select('departments.id as department_id', 'departments.name_en as department_name', 'departments.code as name_abr')
            ->where([
                ['departments.active', '=', true],
                ['is_specialist', '=', true],
                ['parent_id', '=', 11]
            ])
            ->orderBy('name_abr', 'ASC')
            ->get('departments.department_id');

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
        $totalCourseInSemester = [];
        $department_id = explode('_', $_GET['id'])[1];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;

        $allTeachers = $this->getAllteacherByDeptId($department_id);

            foreach ($allTeachers as $teacher) {

                $selectedCourses = $this->getCourseAnnualFromDB($teacher->teacher_id, $academic_year_id, $grade_id, $degree_id );

//                dd($selectedCourses);
                $totalCoursePerSemester=[];
                $timeTpS1 =0;
                $timeTpS2 =0;
                $timeTdS1 =0;
                $timeTdS2 =0;
                $timeCourseS1 =0;
                $timeCourseS2 =0;

                if($selectedCourses) {
                    foreach($selectedCourses as $course) {
                        $totalCoursePerSemester[$course->semester_id]=$course->time_tp + $course->time_td + $course->time_course;
                        if($course->semester_id == 1) {

                            $timeTpS1 = $timeTpS1 +  $course->time_tp;
                            $timeTdS1 =$timeTdS1 + $course->time_td;
                            $timeCourseS1 = $timeCourseS1 + $course->time_course;

                        } else {
                            $timeTpS2 = $timeTpS2 +  $course->time_tp;
                            $timeTdS2 =$timeTdS2 + $course->time_td;
                            $timeCourseS2 = $timeCourseS2 + $course->time_course;
                        }

                    }
                }

                if($teacher->department_id == $department_id) {

                    $element = array(
                        "id"        => 'department_'.$department_id.'_teacher_' . $teacher->teacher_id,
                        "text"      => $teacher->teacher_name.' (S1 = '.(isset($totalCoursePerSemester[1]) ? $totalCoursePerSemester[1] : 0). ' | S2 = '.(isset($totalCoursePerSemester[2]) ? $totalCoursePerSemester[2] : 0).')',
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
                        "text" =>  $teacher->teacher_name.' (S1 = '.(isset($totalCoursePerSemester[1]) ? $totalCoursePerSemester[1] : 0). ' | S2 = '.(isset($totalCoursePerSemester[2]) ? $totalCoursePerSemester[2] : 0).')',
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

//        dd($request->all());

        $selectedCourses = $this->getCourseAnnualFromDB($teacher_id, $academic_year_id, $grade_id, $degree_id );
//        dd($selectedCourses);

        if($selectedCourses) {

            foreach($selectedCourses as $course) {

                $element = array(
                    "id" => $parent_id.'_courseannual_' . $course->id,
                    "text" => $course->course_name. ' ('.$course->degree_id.$course->grade_id.')',
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

    public function getAllCourseByDepartment (CourseAnnualAssignmentRequest $request) {

        $deptId = explode('_', $_GET['id'])[1];
        $arrayCourses = [];
        $Course = [];
        $academic_year_id = $request->academic_year_id;
        $grade_id = $request->grade_id;
        $degree_id = $request->degree_id;

        $notSelectedCourses = $this->getNotSelectedCourseByDept($deptId, $academic_year_id, $grade_id, $degree_id);

        if($notSelectedCourses) {

            foreach($notSelectedCourses as $course) {

                $totalCoursePerSemester = $course->time_tp + $course->time_td + $course->time_course;


                $element = array(
                    "id" => 'department_'.$deptId.'_'.'course_' . $course->course_annual_id,
                    "text" => $course->course_name.' (S_'.$course->semester_id.' = '.$totalCoursePerSemester.')',
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

    public function courseAssignment (CourseAnnualAssignmentRequest $request) {


        $academicYear = AcademicYear::where('id', $request->academic_year_id)->first();
        $departmentId = $request->department_id;
        $gradeId = $request->grade_id;
        $degreeId = $request->degree_id;

        return view('backend.course.courseAnnual.includes.popup_course_assignment', compact('academicYear', 'departmentId', 'gradeId', 'degreeId'));
    }


    public function removeCourse (CourseAnnualAssignmentRequest $request) {

        $input = $request->course_selected;
        $nodeID = json_decode($input);

        if(count($nodeID) > 0) {
            $check = 0;
            $uncount =0;

            foreach ($nodeID as $id) {
                $explode = explode('_', $id);
                if(count($explode) > 4) {
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
        $course = $this->getCourseAnnualById($courseAnnualId);
        $allSemesters = Semester::get();

        if($course) {

            return view('backend.course.courseAnnual.includes.popup_edit_course_annual', compact('course', 'allSemesters'));
        }

    }

    public function editCourseAnnual($courseId, CourseAnnualAssignmentRequest $request) {


        $preCourse = $this->getCourseAnnualById($courseId);
//        dd($preCourse);

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
            'active'        => true
        ];

        $update =  $this->courseAnnuals->update($courseId, $inputs);
        if($update) {
            return Response::json(['status'=>true, 'message'=>'update course successfully!']);
        } else {
            return Response::json(['status'=>false, 'message'=>'Error Updating!!']);
        }
    }

    public function douplicateCourseAnnual(CourseAnnualAssignmentRequest $request) {

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
            'grade_id'              => $courseAnnual->grade_id

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
        $departmentId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $check =0;
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

                return Response::json(['status'=> false, 'message'=>'Duplicated Generating!!']);

            } else {
                $store = $this->courseAnnuals->create($input);

                if($store) {
                    $check++;
                }
            }
        }

        if($check == count($courseAnnual)) {
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
            return true;
        } else {
            return false;
        }

    }

    public function getFormScoreByCourse($courseAnnualID) {

        $courseAnnual = $this->getCourseAnnualById($courseAnnualID);

        return view('backend.course.courseAnnual.includes.form_input_score_course_annual', compact('courseAnnualID', 'courseAnnual'));
    }

    public function getCourseAnnualScoreByAjax(Request $request) {

        //-----this is a default columns and columnHeader
        return $this->handsonTableData($request->course_annual_id);
    }

    private function handsonTableData($courseAnnualId) {

        $arrayData = [];
        $columnHeader = array(/*'Student_annual_id',*/'Student ID', 'Student Name', 'Gender', 'Num Absence', 'Absence-10%');
        $columns=  array(
//            ['data' => 'student_annual_id', 'readOnly'=>true],
            ['data' => 'student_id_card', 'readOnly'=>true],
            ['data' => 'student_name', 'readOnly'=>true],
            ['data' => 'student_gender', 'readOnly'=>true],
            ['data' => 'num_absence', 'type' => 'numeric'],
            ['data' => 'absence', 'type' => 'numeric', 'readOnly'=>true]
        );

        $courseAnnual = $this->getCourseAnnualById($courseAnnualId);
        $columnName = $this->getPropertiesFromScoreTable($courseAnnual);
        $columnName = $columnName->select('percentages.name', 'percentages.id as percentage_id')->groupBy('percentages.id')->orderBy('percentages.id')->get();

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId(
            $courseAnnual->department_id,
            $courseAnnual->degree_id,
            $courseAnnual->grade_id,
            $courseAnnual->academic_year_id
        );

        if($columnName) {

            foreach($columnName as $column) {
                $columnHeader = array_merge($columnHeader, array($column->name));
                $columns = array_merge($columns, array(['data'=>$column->name]));
            }
            $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true]));
            $columnHeader = array_merge($columnHeader, array('Average'));

        } else {

            $columns = array_merge($columns, array(['data' => 'average', 'readOnly' => true]));
            $columnHeader = array_merge($columnHeader, array('Average'));
        }

        //----------------find student score if they have inserted

        foreach($studentByCourse as $student) {

            $mergeStudentscore=[];

            $studentScore = $this->getPropertiesFromScoreTable($courseAnnual);//join three table scores, percentages, and score_percentage

            $studentScore = $studentScore->where('student_annual_id', $student->student_annual_id)
                ->select('scores.score', 'scores.score_absence', 'percentages.name', 'percentages.percent', 'percentages.id as percentage_id', 'scores.id as score_id')
                ->get();


            // get number of absence from database
            $scoreAbsence = $this->getAbsenceFromDB($courseAnnual->id, $student->student_annual_id);
            //get total score of one course from DB

//            $totalScore = $this->averages->findAverageByCourseIdAndStudentId($courseAnnual->id, $student->student_annual_id);
            $totalScore = 0;
            $scoreIds = []; // there are many score type for one subject and one student :example TP, Midterm, Final-exam
            if($studentScore) {

//                dd($studentScore);
                foreach($studentScore as $score) {

                    $totalScore = $totalScore + (($score->score * $score->percent)/100);
                    $scoreData[$score->name] = $score->score;
                    $scoreData['percentage_id'.'_'.$score->name] =  $score->percentage_id;
                    $scoreData['score_id'.'_'.$score->name]=$score->score_id;
                    $scoreIds[] = $score->score_id;

                }

            } else{
                $scoreData=[];
            }

            /*------store average(a total score of one courseannual in table averages)-----------*/

            $input = [
                'course_annual_id' => $courseAnnualId,
                'student_annual_id' => $student->student_annual_id,
                'average'   => $totalScore
            ];
            $storeTotalScore = $this->storeTotalScoreEachCourseAnnual($input, $scoreIds); // private function to store of update total score

            /*------------end of insert of update total score -------------*/

            $element = array(
                'student_annual_id'=>$student->student_annual_id,
                'student_id_card' => $student->id_card,
                'student_name' => $student->name_latin,
                'student_gender' => $student->code,
                'absence'          => isset($scoreAbsence) ? 10-$scoreAbsence->num_absence:10,
                'num_absence'      => isset($scoreAbsence) ? $scoreAbsence->num_absence:0,
//                'average'          => isset($totalScore) ? (float)$totalScore->average: null,
                'average'          => $totalScore,

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


//        dd($arrayData);
        return json_encode([
            'data' => $arrayData,
            'columnHeader' => $columnHeader,
            'columns'      =>$columns
        ]);


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

    private function getAbsenceFromDB($courseAnnualID, $studentAnnualID) {

        $absence = DB::table('absences')
            ->where([
                ['course_annual_id', $courseAnnualID],
                ['student_annual_id', $studentAnnualID]
            ])
            ->first();

        return $absence;
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
                'genders.code'
            )
            ->orderBy('students.id_card', 'ASC')
            ->get();


        return $studentAnnual;

    }

    public function insertPercentageNameNPercentage(Request $request) {

    //this is to add new column name of the exam score ...and we have to initial the value 0 to the student for this type of exam

        $check =0;
        $courseAnnual = $this->getCourseAnnualById($request->course_annual_id);

        $percentageInput = [
            'name'              =>   $request->percentage_name,
            'percent'           => $request->percentage,
            'percentage_type'   => $request->percentage_type
        ];

        $savePercentageId = $this->percentages->create($percentageInput);// return the percentage id

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId(
            $courseAnnual->department_id,
            $courseAnnual->degree_id,
            $courseAnnual->grade_id,
            $courseAnnual->academic_year_id
        );

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
                    'socre_absence'     =>  0

                ];

                $saveScoreId = $this->courseAnnualScores->create($input);// return the socreId
                $savePercentageScore = $this->courseAnnualScores->createPercentageScore($saveScoreId->id, $savePercentageId->id);

                if($savePercentageScore) {

                    $check++;
                }
            }
        }
        if($check == count($studentByCourse)) {

            $reDrawTable = $this->handsonTableData($request->course_annual_id);
            return $reDrawTable;
        }

    }

    public function storeNumberAbsence(Request $request) {


        $baseData = $request->baseData;
//        dd($baseData);

//        dd($baseData);
        $checkStore = 0;
        $checkUpdate=0;
        $checkNOTUpdatOrStore = 0;


        if(count($baseData) > 0) {
            foreach($baseData as $data) {

                if($data['student_annual_id'] != null) {

                    $absence = $this->absences->findIfExist($data['course_annual_id'], $data['student_annual_id']);

//                dd($absence);

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
        }

        if($checkStore+$checkUpdate == count($baseData)- $checkNOTUpdatOrStore) {
            $reDrawTable = $this->handsonTableData($data['course_annual_id']);
            return $reDrawTable;
        }

    }

    public function deleteScoreFromScorePercentage(Request $request) {

        $checkDeleteScore =0;
        $percentageScore = Percentage::join('percentage_scores', 'percentage_scores.percentage_id', '=', 'percentages.id')
            ->join('scores', 'scores.id', '=', 'percentage_scores.score_id')
            ->where('percentages.id', $request->percentage_id)
            ->lists('scores.id');

        $deletePercentage = $this->percentages->destroy($request->percentage_id);

        foreach($percentageScore as $score) {
            $deleteScore = $this->courseAnnualScores->destroy($score);
            DB::table('percentage_scores')->where([
                ['percentage_id', '=', $request->percentage_id],
                ['score_id', '=', $score]
            ])->delete();

            if($deleteScore) {
                $checkDeleteScore++;
            }
        }

        if($checkDeleteScore == count($percentageScore)) {

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
                $check = true;
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
                    $check =true;
                }
            }
        }

        if($check == true) {
            return true;
        } else {
            return false;
        }

    }

//    --------------course annual score evaluation ---------------


    public function formScoreAllCourseAnnual(Request $request) {

        $deptId = $request->department_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $academicYearID = $request->academic_year_id;
        $semesterId = $request->semester_id;

        $students = $this->getStudentByDeptIdGradeIdDegreeId($deptId, $degreeId, $gradeId, $academicYearID);

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

        $courseAnnuals = $courseAnnuals->orderBy('semester_id')->get();



        $department = Department::where('id', $deptId)->first();
        $degree = Degree::where('id', $degreeId)->first();
        $grade = Grade::where('id', $gradeId)->first();
        $academicYear = AcademicYear::where('id', $academicYearID)->first();
        $semesters = Semester::get();

//        dd($semesters);


        return view('backend.course.courseAnnual.includes.form_all_score_courses_annual', compact('department', 'degree', 'grade', 'academicYear', 'semesters', 'semesterId', 'courseAnnuals', 'students'));

    }


    public function allHandsontableData(Request $request) {

        $deptId = $request->dept_id;
        $degreeId = $request->degree_id;
        $gradeId = $request->grade_id;
        $academicYearID = $request->academic_year_id;
        $semesterId = $request->semester_id;

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

        $students = $this->getStudentByDeptIdGradeIdDegreeId($deptId, $degreeId, $gradeId, $academicYearID);
        $courseAnnuals = $courseAnnuals->orderBy('semester_id')->get();

        $arrayData = [];
        $arraySemester = [];

        $colWidths=  [100, 100, 180, 50, 75, 75, 75];

        $allProperties = $this->getCourseAnnualWithScore($courseAnnuals);
        $averages = $allProperties['averages'];
        $absences = $allProperties['absences'];
        $arrayCourseScore = $allProperties['arrayCourseScore'];

        if($semesterId) {
            $nestedHeaders =  [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label'=> 'Absents', 'colspan'=> 2]
                ],
                ['Order','', '', '',
                    ['label'=> 'Total', 'colspan'=>1],
                ]
            ];
            $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'S_'.$semesterId, 'colspan'=>1]]);
        } else {
            $semesters = Semester::orderBy('id')->get();
            $nestedHeaders =  [
                ['', 'Student ID', 'Student Name', 'Sexe',
                    ['label'=> 'Absents', 'colspan'=> 3]
                ],
                ['Order','', '', '',
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
        if($courseAnnuals) {
            $creditInEachSemester =  [];
            foreach($courseAnnuals as $courseAnnual) {

                $creditInEachSemester[$courseAnnual->semester_id][] = $courseAnnual->course_credit;
                $nestedHeaders[0] = array_merge($nestedHeaders[0], [['label'=>'S'.$courseAnnual->semester_id.'_'.$courseAnnual->course_name, 'colspan'=>2]]);
                $nestedHeaders[1] = array_merge($nestedHeaders[1], [['label'=>'Abs', 'colspan'=>1], ['label'=> $courseAnnual->course_credit, 'colspan'=>1]]);
                $colWidths[] = 150;
                $colWidths[] = 150;
            }
//            dd(array_sum($creditInEachSemester[1]));
            $finalCredit=0;
            if($semesterId) {
                $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S'.$semesterId.'_Moyenne']);
                $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum($creditInEachSemester[$semesterId])]);
            } else {
                $semesters = Semester::orderBy('id')->get();
                if($semesters) {
                    foreach($semesters as $semester) {
                        $nestedHeaders[0] = array_merge($nestedHeaders[0], ['S'.$semester->id.'_Moyenne']);
                        $nestedHeaders[1] = array_merge($nestedHeaders[1], [array_sum($creditInEachSemester[$semester->id])]);
                        $finalCredit= $finalCredit + array_sum($creditInEachSemester[$semester->id]);
                    }
                }
            }

            $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Moyenne']);
            $nestedHeaders[0] = array_merge($nestedHeaders[0], ['Classement']);
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
            $colWidths[] = 150;
            $colWidths[] = 150;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;
            $colWidths[] = 100;

        }

        if($students) {
            $index =0;
            $allCredit = 0;// the same as total credit
            $finalMoyennes =0;
            $allMoyenneScoreOfStudentBySemester=[];
            $allMoyenneYearly=[];
            foreach($students as $student) {
                $index++;
                $element = array(
                    'number' => ' '.$index,
                    'student_id_card' => $student->id_card,
                    'student_name' => $student->name_latin,
                    'student_gender' => $student->code,
                    'total' => 0,
                );
                if($semesterId) {
                    $element = $element +['S_'.$semesterId => 0];

                    if($courseAnnuals) {

                        $count=0;
                        $totalCredit=0;// for only each one semester
                        $moyenne = 0;
                        $totalAbs = 0;
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
                    $element = $element +['Moyenne'=>number_format((float)($moyenne/$totalCredit), 2, '.', '')];
                    $allMoyenneYearly[] = number_format((float)($moyenne/$totalCredit), 2, '.', '');// array of total average for each student

                    $ranks[] = number_format((float)($moyenne/$totalCredit), 2, '.', '');

                } else {

                    // if the user did not select the semester .....so that mean we need to score for all semesters

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
                                $moyenne=0;
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
                            }
                            // --- here we add value to column moyenne by semester and assign value for each absence of the semester

                            $allMoyenneScoreOfStudentBySemester[$semester->id][] = number_format((float)($moyenne/$creditBySemester), 2, '.', '');// push all moyenne of all student by semester id

                            $element['S_'.$semester->id]= $absBySemester;
                            $totalAbseneces = $totalAbseneces + $absBySemester;
                            $semesterMoyenne[$semester->id]= number_format((float)($moyenne/$creditBySemester), 2, '.', '');
                            $finalMoyennes = $finalMoyennes + ($moyenne);
                        }

                        foreach($semesters as $semester) {
                            $element = $element +['S'.$semester->id.'_Moyenne'=> $semesterMoyenne[$semester->id]];
                        }
                    }

                    $element['total']= $totalAbseneces;
                    $element = $element +['Moyenne'=>number_format((float)($finalMoyennes/$allCredit), 2, '.', '')];
                    $allMoyenneYearly[] = number_format((float)($finalMoyennes/$allCredit), 2, '.', '');// array of total average for each student
                    $ranks[] = number_format((float)($finalMoyennes/$allCredit), 2, '.', '');
                }



                $element = $element +['Classement'=>0];
                $element = $element +['Redouble'=>' '];
                $element = $element +['Observation'=> ' '];
                $element = $element +['Rattrapage'=>' '];
                $element = $element +['Passage'=>' '];
                $element = $element +[' '=>' '];

                $arrayData[] = $element;
            }
        }
        asort($ranks);
        $ranks = array_reverse($ranks);
        $finalData=[];
        for($index=0; $index< count($ranks); $index++) {
            foreach($arrayData as $data) {
                if($data['Moyenne'] == $ranks[$index]) {
                    $data['Classement'] = $index+1;
                    $data['number'] = $index+1;
                    $finalData[] = $data;
                }
            }
        }
        $arrayData = $finalData;
//        dd($arrayData);

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

            $semesterMaxMoyen = $semesterMaxMoyen +  ['S'.$semesterId.'_Moyenne'=> max($allMoyenneScoreOfStudentBySemester[$semesterId])];
            $semesterMinMoyen = $semesterMinMoyen + ['S'.$semesterId.'_Moyenne'=> min($allMoyenneScoreOfStudentBySemester[$semesterId])];
            $semesterAverageMoyen =$semesterAverageMoyen + ['S'.$semesterId.'_Moyenne'=> number_format((float)((array_sum($allMoyenneScoreOfStudentBySemester[$semesterId])/count($allMoyenneScoreOfStudentBySemester[$semesterId]))), 2,'.', '')];
        } else {
            if($semesters) {

                foreach($semesters as $semester) {
                    $dataEmpty[] = '';
                    $maxArray = $maxArray +['S_'.$semester->id => ''];
                    $minArray = $minArray +['S_'.$semester->id => ''];
                    $averageArray = $averageArray +['S_'.$semester->id => ''];

                    $semesterMaxMoyen = $semesterMaxMoyen +  ['S'.$semester->id.'_Moyenne'=> max($allMoyenneScoreOfStudentBySemester[$semester->id])];
                    $semesterMinMoyen = $semesterMinMoyen + ['S'.$semester->id.'_Moyenne'=> min($allMoyenneScoreOfStudentBySemester[$semester->id])];
                    $semesterAverageMoyen =$semesterAverageMoyen + ['S'.$semester->id.'_Moyenne'=> number_format((float)((array_sum($allMoyenneScoreOfStudentBySemester[$semester->id])/count($allMoyenneScoreOfStudentBySemester[$semester->id]))), 2,'.','')];

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

        $maxArray = $maxArray+ ['Moyenne'=>max($allMoyenneYearly)];
        $minArray = $minArray+ ['Moyenne'=>min($allMoyenneYearly)];
        $averageArray = $averageArray+ ['Moyenne'=>number_format((float)((array_sum($allMoyenneYearly)/count($allMoyenneYearly))), 2, '.', '')];

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

    private function getScoreEachCourse($courseAnnualId, $studentAnnualId) {

        $score = Average::where([
            ['course_annual_id', $courseAnnualId],
            ['student_annual_id', $studentAnnualId]
        ])->first();

        return $score;

    }

    private function getCourseAnnualWithScore($courseAnnually) {// ---$courseAnnually---collections of all courses by dept, grade, semester ...

        $arrayAverageObject=[];
        $arrayAbsenceObject=[];
        $arrayScoreOneCourseWithAllStudents = [];

        foreach($courseAnnually as $courseAnnual) {
            $averageProperties = DB::table('averages')->where('course_annual_id', $courseAnnual->course_annual_id)->orderBy('student_annual_id')->get();

            if($averageProperties) {
                foreach($averageProperties as $property) {
                    $arrayAverageObject[$courseAnnual->course_annual_id][$property->student_annual_id] = $property;
                    $arrayScoreOneCourseWithAllStudents[$courseAnnual->course_annual_id][] = $property->average;
                }
            }

            $absenceProperties = DB::table('absences')->where('course_annual_id', $courseAnnual->course_annual_id)->get();

            if($absenceProperties) {
                foreach($absenceProperties as $absenceProperty) {
                    $arrayAbsenceObject[$courseAnnual->course_annual_id][$absenceProperty->student_annual_id] = $absenceProperty;
                }
            }
        }

//        dd($arrayScoreOneCourseWithAllStudents);

        return ['averages'=>$arrayAverageObject,'absences'=>$arrayAbsenceObject, 'arrayCourseScore'=>$arrayScoreOneCourseWithAllStudents] ;



    }
}
