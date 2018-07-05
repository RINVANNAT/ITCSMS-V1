<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Backend\Course\CourseHelperTrait\CourseProgramTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseProgram\CreateCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\DeleteCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\EditCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\ImportCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\RequestImportCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\StoreCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\UpdateCourseProgramRequest;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Semester;
use App\Repositories\Backend\CourseProgram\CourseProgramRepositoryContract;
use App\Utils\ArrayUtils;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class CourseController
 * @package App\Http\Controllers\Backend\Course
 */
class CourseController extends Controller
{

    use CourseProgramTrait;
    /**
     * @var CourseProgramRepositoryContract
     */
    protected $coursePrograms;

    /**
     * @param CourseProgramRepositoryContract $courseProgramRepo
     */
    public function __construct(
        CourseProgramRepositoryContract $courseProgramRepo
    )
    {
        $this->coursePrograms = $courseProgramRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $academicYears = AcademicYear::lists("name_en", "id");
//        $degrees = Degree::lists("name_en", "id");
//        $grades = Grade::lists("name_en", "id");
//        $departments = Department::lists("name_en", "id");
        $responsible_departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
        $semesters = Semester::lists("name_en", "id");

        if(auth()->user()->allow("view-all-score-in-all-department")){

            // Get all department in case user have previlege to view all department
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $department_id = null;
            $deptOptions = [];

        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $department_id = $employee->department->id;
            $deptOptions = $this->deptHasOption($department_id);
        }

        $academicYears = AcademicYear::orderBy("id","desc")->lists('name_latin','id')->toArray();
        $degrees = Degree::lists('name_en','id')->toArray();
        $grades = Grade::lists('name_en','id')->toArray();


        return view('backend.course.courseProgram.index', compact(
            "degrees", "grades", "departments",
            "semesters", "academicYears", 'deptOptions',
            'department_id', 'semesters',
            'responsible_departments'
        ));

    }

    public function getDeptOption(Request $request) {

        $deptOptions = $this->deptHasOption($request->department_id);

        return view('backend.course.courseAnnual.includes.dept_option_selection', compact('deptOptions'));
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


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseProgramRequest $request)
    {

        if(auth()->user()->allow("view-all-score-in-all-department")){
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $other_departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $options = DepartmentOption::get();
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $other_departments = Department::where("parent_id",config('access.departments.department_academic'))
                                            ->where("id", "!=", $employee->department_id)
                                            ->orderBy("code")
                                            ->lists("code","id");
            $options = DepartmentOption::where('department_id',$employee->department_id)->get();
        }

        $degrees = Degree::lists("name_en", "id");
        $grades = Grade::lists("name_en", "id");
        $semesters = Semester::lists("name_en", "id");
        return view('backend.course.courseProgram.create', compact("degrees", "grades", "departments", "semesters","options","other_departments"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseProgramRequest $request)
    {

        //$this->validate($request, Course::$rules);
        $this->coursePrograms->create($request->all());
        return redirect()->route('admin.course.course_program.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param EditCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCourseProgramRequest $request, $id)
    {
        $courseProgram = $this->coursePrograms->findOrThrowException($id);
        if(auth()->user()->allow("view-all-score-in-all-department")){
            $departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
            $options = DepartmentOption::get();
            $other_departments = Department::where("parent_id",config('access.departments.department_academic'))->orderBy("code")->lists("code","id");
        } else {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $departments = $employee->department()->lists("code","id");
            $options = DepartmentOption::where('department_id',$employee->department_id)->get();
            $other_departments = Department::where("parent_id",config('access.departments.department_academic'))
                ->where("id", "!=", $employee->department_id)
                ->orderBy("code")
                ->lists("code","id");
        }

        $degrees = Degree::lists("name_en", "id");
        $grades = Grade::lists("name_en", "id");
        $semesters = Semester::lists("name_en", "id");

        return view('backend.course.courseProgram.edit', compact('courseProgram','degrees','grades','departments','semesters','options','other_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseProgramRequest $request, $id)
    {
        $this->coursePrograms->update($id, $request->all());
        return redirect()->route('admin.course.course_program.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCourseProgramRequest $request, $id)
    {
        $this->coursePrograms->destroy($id);
        if ($request->ajax()) {
            return json_encode(array('success' => 'true'));
        } else {
            return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data(Request $request)
    {

        $coursePrograms = DB::table('courses')
            ->join('semesters', 'semesters.id', '=', 'courses.semester_id')
            ->leftJoin('grades', 'courses.grade_id', '=', 'grades.id')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
            ->leftJoin("departmentOptions", 'courses.department_option_id', '=', "departmentOptions.id")
            ->leftJoin('departments as rd', 'courses.responsible_department_id', '=', 'rd.id')
            ->leftJoin('degrees', 'courses.degree_id', '=', 'degrees.id')
            ->select([
                'courses.id as course_id',
                'courses.name_kh',
                'courses.name_en',
                'courses.name_fr',
                'courses.code',
                'courses.time_tp',
                'courses.time_td',
                'courses.time_course',
                'courses.credit',
                'departmentOptions.code as option',
                'semesters.name_en as semester',
                'rd.code as responsible_department',
                'courses.active',
                DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
            ])
            ->orderBy('courses.department_id','ASC')
            ->orderBy('courses.degree_id','ASC')
            ->orderBy('courses.grade_id','ASC')
            ->orderBy('courses.semester_id','ASC');

        $datatables = app('datatables')->of($coursePrograms);

        if ($degree = $datatables->request->get('degree')) {
            $datatables->where('courses.degree_id', '=', $degree);
        }
        if ($grade = $datatables->request->get('grade')) {
            $datatables->where('courses.grade_id', '=', $grade);
        }
        if ($department = $datatables->request->get('department')) {
            $datatables->where('courses.department_id', '=', $department);
        }
        if ($deptOptionId = $datatables->request->get('department_option')) {
            $datatables->where('courses.department_option_id', '=', $deptOptionId);
        }
        if ($responsible_department = $datatables->request->get('responsible_department')) {
            $datatables->where('courses.responsible_department_id', '=', $responsible_department);
        }
        if ($semester = $datatables->request->get('semester')) {
            $datatables->where('courses.semester_id', '=', $semester);
        }
        if(auth()->user()->allow("view-all-score-in-all-department")){ // user has permission to view all course/score in all department
            if ($department = $datatables->request->get('department')) {
                $datatables->where('courses.department_id', '=', $department);
            }
        } else {
            $employee =Employee::where('user_id', Auth::user()->id)->first();
            $datatables = $datatables ->where('courses.department_id', $employee->department->id );
        }

        if($request->deactive == true) {
            $datatables = $datatables -> where('courses.active', false);
        } else {
            $datatables = $datatables -> where('courses.active', true);
        }

        $datatables
            ->editColumn('name_kh' , function($courseProgram) {
                return '<b>'.$courseProgram->name_kh.'</b><br/>'.$courseProgram->name_en.'<br/>'.$courseProgram->name_fr;
            })
            ->editColumn('class' , function($courseProgram) {
                return $courseProgram->class.$courseProgram->option;
            })
            ->addColumn('action', function ($courseProgram) {
                $actions = "";

                if(Auth::user()->allow('edit-coursePrograms')) {
                    $actions = $actions.'<a href="' . route('admin.course.course_program.edit', $courseProgram->course_id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.crud.edit') . '"></i> </a>';
                }
                if(Auth::user()->allow('delete-coursePrograms')) {

                    if ($courseProgram->active == true){
                        $actions = $actions.' <a href="' . route('admin.course.course_program.activate', $courseProgram->course_id) . '" class="btn btn-xs btn-warning"><i class="fa fa-retweet" data-toggle="tooltip" data-placement="top" title="Deactivate"></i></a>';
                    } else {
                        $actions = $actions.' <a href="' . route('admin.course.course_program.activate', $courseProgram->course_id) . '" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Activate"></i></a>';
                    }
                    $courseAnnuals = CourseAnnual::where('course_id', $courseProgram->course_id)->count();
                    if ($courseAnnuals == 0) {
                        $actions = $actions.' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.course.course_program.destroy', $courseProgram->course_id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                    }
                }
                return  $actions;
            });
        
        return $datatables->make(true);
    }

    public function activate($id)
    {
        $courseProgram = Course::find($id);
        if($courseProgram instanceof Course) {
            if ($courseProgram->active == true){
                $courseProgram->active = false;
            }else {
                $courseProgram->active = true;
            }

            if ($courseProgram->save()){
                return redirect()->route('admin.course.course_program.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
            }
        }
    }

    public function request_import(RequestImportCourseProgramRequest $request)
    {
        return view('backend.course.courseProgram.import');
    }

    public function import(ImportCourseProgramRequest $request)
    {
        $now = Carbon::now()->format('Y_m_d_H');
        if ($request->file('import') != null) {
            $import = $now . '.' . $request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/temp/' . $import;

            // validation file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $fileType = finfo_file($finfo, $storage_path) . "\n";
            finfo_close($finfo);

            $fileContext = array(
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.ms-excel",
                "text/plain",
            );

            if (in_array($fileType, $fileContext)) {
                Flash::error('file type is ' . $fileType . ' Only excel or csv that import:' . $fileType . " key:");
                return redirect()->back();
            }
            // validation header
            $GLOBALS['countRow'] = 0;
            DB::beginTransaction();
            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {
                    $requireKey = array_keys(Course::$rules);
                    $aRow = array_keys($results->first()->toArray());
                    $result = count((array_intersect($aRow, $requireKey))) >= count($requireKey) ? true : false;
                    if ($result == false) {
                        foreach ($requireKey as $requireKey2) {
                            if (!in_array($requireKey2, $aRow)) {
                                Flash::error('import file should have header ' . $requireKey2);
                                return redirect()->route('admin.course.course_program.request_import');
                            } else {

                            };

                        }

                    }

                    $results->each(function ($row) {
                        $courseData = $row->toArray();
                        $courseData["created_at"] = Carbon::now();
                        $courseData["create_uid"] = auth()->id();
                        $courseAnnual = Course::create($courseData);

                        $GLOBALS['countRow'] = $GLOBALS['countRow'] + 1;
                    });
                });

            } catch (Exception $e) {
                DB::rollback();
//              todo change id sequence to last one. "ALTER SEQUENCE couress_id_seq RESTART WITH 1";
                Flash::error('data is not correct format ');
                return redirect()->back();

            }
            DB::commit();
            Flash::success('Import Successfully ' . $GLOBALS['countRow'] . ' rows effected');
            return redirect()->route('admin.course.course_program.index');
        }


    }

    public function request_import_config(RequestImportCourseProgramRequest $request)
    {
        return view('backend.course.courseProgram.import_config');
    }


    public function import_config(ImportCourseProgramRequest $request)
    {
        $now = Carbon::now()->format('Y_m_d_H');
        $messageSuccess = "";
        if ($request->file('import') != null) {
            $import = $now . '.' . $request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );
            
            $storage_path = base_path() . '/public/assets/uploaded_file/temp/' . $import;
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $fileType = finfo_file($finfo, $storage_path) . "\n";
            finfo_close($finfo);
            $fileContext = array(
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.ms-excel",
                "text/plain",
            );

            if (in_array($fileType, $fileContext)) {
                Flash::error('file type is ' . $fileType . ' Only excel or csv that import:' . $fileType . " key:");
                return redirect()->back();
            }
            $GLOBALS['countRow'] = 0;
            $GLOBALS['countRowEmployee'] = 0;
            $GLOBALS['employees'] = array();

            
            /**
            * 1. Import Employee
            */

//
            DB::beginTransaction();
            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {
                    $aRow = array_keys($results->first()->toArray());
                    $results->each(function ($row) {
                        $employeeData = array();
                        $employeeData["name_kh"] = $row["lecturer"];
                        $employeeData["name_latin"] = $row["lecturer"];
                        $employeeData["email"] = "E-mail";
                        $employeeData["phone"] = "";
                        $employeeData["active"] = "true";
                        $employeeData["gender_id"] = "1";
                        $employeeData["birthdate"] = "15/06/2016";
                        $employeeData["assignees_roles"] = array("3");
                        $employeeData["department_id"] = $row["department_id"];
                        $employeeData["created_at"] = Carbon::now()->format('Y_m_d_H');
                        $employeeData["create_uid"] = auth()->id();
                        array_push($GLOBALS['employees'], $employeeData);
                    });
                });

            } catch (Exception $e) {
//                return redirect()->back();
            }
            $countNewEmployeeImport = 0 ;
            try {
                $uniqueEmployees = ArrayUtils::unique_multidim_array($GLOBALS['employees'], "name_latin");
                foreach ($uniqueEmployees as $employee){
                    $employeeDataBase = Employee::where('name_latin', $employee['name_latin'])->first();
                    if($employeeDataBase == null) {
                        if ($employee['name_latin']==null){
                            Flash::error("name of lecture can not be empty");
                            return redirect()->back();
                        }
                        Employee::create($employee);
                        $countNewEmployeeImport = $countNewEmployeeImport + 1;
                    }
                }
                $messageSuccess = "Number of Employee that had been import: ".$countNewEmployeeImport."<br>";
            } catch (Exception $e) {
                DB::rollback();
                Flash::error('Employee Error: Data is not correct format ');
            }

            /**
            * 2. Import Course
            *
            */


            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {
                    $results->each(function ($row) {
                        $courseData = $row->toArray();
                        $courseDataObject = new Course;
                        $courseDataObject->name_kh = $courseData['name_kh'];
                        $courseDataObject->name_en = $courseData['name_en'];
                        $courseDataObject->name_fr = $courseData['name_fr'];
                        $courseDataObject->code = $courseData['code'];
                        $courseDataObject->time_course = $courseData['time_course'];
                        $courseDataObject->time_td = $courseData['time_td'];
                        $courseDataObject->time_tp = $courseData['time_tp'];
                        $courseDataObject->credit = floatval($courseData['credit']);
                        $courseDataObject->semester_id = $courseData['semester_id'];
                        $courseDataObject->degree_id = $courseData['degree_id'];
                        $courseDataObject->department_id = $courseData['department_id'];
                        $courseDataObject->grade_id = $courseData['grade_id'];
                        $courseDataObject->create_uid = auth()->id();
                        $courseDataObject->write_uid = auth()->id();
                        $courseDataObject->active = true;
                        $courseDataObject->updated_at = Carbon::now();
                        $courseDataObject->created_at = Carbon::now();
                        $courseDataObject->save();
//                        Course::create($courseData);
                        $GLOBALS['countRow'] = $GLOBALS['countRow'] + 1;
                    });
                });
                $messageSuccess = $messageSuccess." number of course have been create ".$GLOBALS['countRow'];

            } catch (Exception $e) {
                DB::rollback();
                Flash::error('Course Error: Data is not correct format ');
                return redirect()->back();
            }
//
//
//
            /**
            * 3. Import Course Annual
            *
            */
            $GLOBALS['courseAnnuals'] = array();

            try {
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function ($results) {

//                    $requireKey = array_keys(CourseAnnual::$rules);
//                    $aRow = array_keys($results->first()->toArray());
//                    $result = count((array_intersect($aRow, $requireKey))) >= count($requireKey) ? true : false;
//                    if ($result == false) {
//                        foreach ($requireKey as $requireKey2) {
//                            if (!in_array($requireKey2, $aRow)) {
//                                Flash::error('Course Annual Error: import file should have header ' . $requireKey2);
//                                return redirect()->route('admin.course.course_program.request_import_config');
//                            }
//                        }
//                    }
                    $results->each(function ($row) {
                        $courseAnnualData = $row->toArray();

                        $employeeDataBase = Employee::where('name_latin', $row['lecturer'])->first();
                        if($employeeDataBase != null) {
                            $courseAnnualData['employee_id'] = $employeeDataBase->id;
                        }else{
                            $courseAnnualData['employee_id'] = auth()->id();
                        }


                        $courseTmp = Course::where('name_en', $row['name_en'])->first();
                        if($courseTmp != null) {
                            $courseAnnualData['course_id'] = $courseTmp->id;
                        }else{
                            $courseTmp = Course::where('name_fr', $row['name_fr'])->first()->get();
                            $courseAnnualData['course_id'] = $courseTmp->id;
                        }
                        $courseAnnualData["created_at"] = Carbon::now();
                        $employeeDataBase["create_uid"] = auth()->id();


                        CourseAnnual::create($courseAnnualData);
//                        array_push($GLOBALS['courseAnnuals'], $courseAnnualData);
                    });
                });

            } catch (Exception $e) {
                DB::rollback();
                Flash::error('Course Annual Error:  data is not correct format ');
                return redirect()->back();
            }
//
            DB::commit();
            Flash::success('Import Successfully ' . $GLOBALS['countRow'] . ' rows effected');
            return redirect()->route('admin.course.course_program.index');
        }
    }
}
