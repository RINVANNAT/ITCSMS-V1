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

}
