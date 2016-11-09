<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseAnnual\CreateCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\DeleteCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\EditCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\StoreCourseAnnualRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\UpdateCourseAnnualRequest;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Grade;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
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

    /**
     * @param CourseAnnualRepositoryContract $courseAnnualRepo
     */
    public function __construct(
        CourseAnnualRepositoryContract $courseAnnualRepo
    )
    {
        $this->courseAnnuals = $courseAnnualRepo;
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

        $employees = Employee::lists("name_latin","id")->toArray();
        return view('backend.course.courseAnnual.index',compact('departments','academicYears','degrees','grades','courses','employees'));
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
        $courses = Course::orderBy('updated_at', 'desc')->lists('name_kh','id')->toArray();
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
        $courses = Course::lists('name_kh','id')->toArray();
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
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
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


    private function getCourseAnnualFromDB ($teacherId) {

            $courseAnnuals = DB::table('course_annuals')
                ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
                ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
                ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
                ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
//            ->leftJoin('grades','course_annuals.grade_id', '=', 'grade.id')

                ->orderBy("course_annuals.updated_at","desc")
                ->select(
                    ['course_annuals.id',
                        'courses.name_en as course_name',
                        'courses.id as course_id',
                        'course_annuals.semester_id',
                        'course_annuals.active',
                        'course_annuals.academic_year_id',
                        'employees.name_latin as employee_id',
                        'departments.code as department_id',
                        'degrees.code as degree_id',
//                    'grades.code  as grade_id',
                        'courses.grade_id',
                        'course_annuals.course_id']
                )
                ->where('employees.id', $teacherId)
                ->get();

        return $courseAnnuals;
    }

    private function getNotSelectedCourseByDept($list, $deptId) {


        $courseAnnuals = DB::table('courses')
            ->leftJoin('departments','courses.department_id', '=', 'departments.id')
            ->leftJoin('degrees','courses.degree_id', '=', 'degrees.id')
            ->select([
                    'courses.name_en as course_name',
                    'courses.id as course_id',
                    'departments.code as department_id',
                    'degrees.code as degree_id',
                    'courses.grade_id'
                ])
            ->where('departments.id', $deptId)
            ->whereNotIn('courses.id', $list)
            ->get();

        return $courseAnnuals;

    }

    private function getSelectedCourses ($deptID) {

        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')
            ->leftJoin('departments','course_annuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees','course_annuals.degree_id', '=', 'degrees.id')
            ->where('departments.id', $deptID)
            ->where('course_annuals.employee_id', null)
            ->distinct('courses.id')
            ->lists('courses.id');

        return $courseAnnuals;

    }

    private function checkDepartmentID($userId) {

        $deptIDbyUserID = DB::table('users')
            ->join('employees', 'users.id', '=' , 'employees.user_id')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->where('users.id', $userId)
            ->select('departments.id')->first();
        if($deptIDbyUserID) {
            return $deptIDbyUserID;
        } else {
            return [];
        }
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

    public function getAllDepartments() {

        $allDepartments= [];

        $userId = auth()->user()->id;
        $deptID = $this->checkDepartmentID($userId);

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

            if($deptID->id == $dept->department_id) {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => 'Department '.$dept->name_abr,
                    "children" => true,
                    "type" => "department",
                    "state" => ["opened" => true, "selected" => false ]
                );
                array_push($allDepartments, $element);


            } else {

                $element = array(
                    "id" => 'department_' . $dept->department_id,
                    "text" => $dept->name_abr,
                    "children" => true,
                    "type" => "department"
                );
                array_push($allDepartments, $element);
            }
        }

        return Response::json($allDepartments);
    }

    public function getAllTeacherByDepartmentId () {

        $teachers = [];
        $userId = auth()->user()->id;
        $deptID = $this->checkDepartmentID($userId);
        $department_id = explode('_', $_GET['id'])[1];

        $allTeachers = $this->getAllteacherByDeptId($department_id);

        foreach ($allTeachers as $teacher) {

            if($deptID->id == $department_id) {

                $element = array(
                    "id" => 'department_'.$department_id.'_teacher_' . $teacher->teacher_id,
                    "text" => $teacher->teacher_name,
                    "children" => true,
                    "type" => "teacher",
                    "state" => ["opened" => true, "selected" => false ]
                );
                array_push($teachers, $element);

            } else {
                $element = array(
                    "id" => 'department_'.$department_id.'_teacher_' . $teacher->teacher_id,
                    "text" => $teacher->teacher_name,
                    "children" => true,
                    "type" => "teacher"
                );
                array_push($teachers, $element);
            }


        }

        return Response::json($teachers);

    }

    public function getSeletedCourseByTeacherID() {

        $courses = [];
        $arayCourse =[];

        $parent_id = $_GET['id'];

        $teacher_id = explode('_', $_GET['id'])[3];

        $selectedCourses = $this->getCourseAnnualFromDB($teacher_id);

        if($selectedCourses) {

            foreach($selectedCourses as $course) {

                $element = array(
                    "id" => $parent_id.'_courseannual_' . $course->id,
                    "text" => $course->course_name. ' ('.$course->degree_id.$course->grade_id.')',
                    "children" => false,
                    "type" => "course"
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

    public function getAllCourseByDepartment () {

        $deptId = explode('_', $_GET['id'])[1];
        $arrayCourses = [];
        $arayCourse = [];
        $Ids = $this->getSelectedCourses($deptId);



        $notSelectedCourses = $this->getNotSelectedCourseByDept($Ids, $deptId);

        if($notSelectedCourses) {

            foreach($notSelectedCourses as $course) {

                $element = array(
                    "id" => 'department_'.$deptId.'course_' . $course->course_id,
                    "text" => $course->course_name. '('.$course->degree_id.'-'.$course->grade_id.')',
                    "children" => false,
                    "type" => "course"
                );
                array_push($arrayCourses, $element);

            }

        }

        $res = array_map("unserialize", array_unique(array_map("serialize", $arrayCourses)));

        foreach ($res as $a) {
            array_push($arayCourse, $a);
        }
        return Response::json($arayCourse);

    }

    public function courseAssignment () {

//        dd(auth()->user()->id);
        return view('backend.course.courseAnnual.includes.popup_course_assignment');
    }


    public function removeCourse (Request $request) {

        $input = $request->course_selected;
        $nodeID = json_decode($input);

        if(count($nodeID) > 0) {
            $check = 0;
            $uncount =0;

            foreach ($nodeID as $id) {
                $explode = explode('_', $id);
                if(count($explode) > 4) {
                    $deparment_id = $explode[1];
                    $teacher_id =  $explode[3];
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

        $courseAnnual = $this->courseAnnuals->findOrThrowException($courseAnnualId);
        $courseAnnual->active = isset($input['active'])?true:false;
        $courseAnnual->employee_id = isset($input['employee_id'])?$input['employee_id']:null;
        $courseAnnual->write_uid = auth()->id();

        if ($courseAnnual->save()) {
            return true;
        }

         throw new GeneralException(trans('exceptions.backend.general.update_error'));

    }

}
