<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\CourseAnnual\CreateCourseAnnualRequest;
use App\Http\Requests\Backend\Configuration\CourseAnnual\DeleteCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseAnnual\EditCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseAnnual\StoreCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseAnnual\UpdateCourseProgramRequest;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Grade;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Backend\Course\CourseAnnual\ImportCourseRequest;
use App\Http\Requests\Backend\Course\CourseAnnual\RequestImportCourseRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CourseAnnual;




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
        $departments = Department::lists('name_kh','id')->toArray();
        $academicYears = AcademicYear::lists('name_kh','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $courses = Course::lists('name_kh','id')->toArray();
        return view('backend.course.courseAnnual.index',compact('departments','academicYears','degrees','grades','courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseAnnualRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseAnnualRequest $request)
    {
        $departments = Department::lists('name_kh','id')->toArray();
        $academicYears = AcademicYear::lists('name_kh','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $courses = Course::lists('name_kh','id')->toArray();

        return view('backend.course.courseAnnual.create',compact('departments','academicYears','degrees','grades','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseProgramRequest $request)
    {
        $this->courseAnnuals->create($request->all());
        return redirect()->route('admin.course.courseAnnuals.index')->withFlashSuccess(trans('alerts.backend.general.created'));
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
    public function edit(EditCourseProgramRequest $request, $id)
    {
        $courseAnnual = $this->courseAnnuals->findOrThrowException($id);

        $departments = Department::lists('name_kh','id')->toArray();
        $academicYears = AcademicYear::lists('name_kh','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $grades = Grade::lists('name_kh','id')->toArray();
        $courses = Course::lists('name_kh','id')->toArray();
        return view('backend.course.courseAnnual.edit',compact('courseAnnual','departments','academicYears','degrees','grades','courses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseProgramRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseProgramRequest $request, $id)
    {
        $this->courseAnnuals->update($id, $request->all());
        return redirect()->route('admin.course.courseAnnuals.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCourseProgramRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCourseProgramRequest $request, $id)
    {
        $this->courseAnnuals->destroy($id);
        return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data(Request $request)
    {
        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->leftJoin('employees','course_annuals.employee_id', '=', 'employees.id')

            ->select(
                ['course_annuals.id',
                    'courses.name_en as name',
                    'course_annuals.semester_id',
                    'course_annuals.active',
                    'course_annuals.academic_year_id',
                    'employees.name_latin as employee_id',
                    'course_annuals.department_id',
                    'course_annuals.degree_id',
                    'course_annuals.grade_id',
                    'course_annuals.course_id']);


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
        return $datatables->make(true);
    }
    public function request_import(RequestImportCourseRequest $request){

        return view('backend.course.courseAnnual.import');

    }

    public function import(ImportCourseRequest $request){

        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            // and then read that data and store to database
            //Excel::load($storage_path, function($reader) {
            //    dd($reader->first());
            //});


            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function($results){
                    //dd($results->first());
                    // Loop through all rows

                    $results->each(function($row) {
                        // Clone an object for running query in studentAnnual
                        $courseAnnual_data = $row->toArray();
                        $courseAnnual_data["created_at"] = Carbon::now();
                        $courseAnnual_data["create_uid"] = auth()->id();
                        $courseAnnual = CourseAnnual::create($courseAnnual_data);


                        if(  $courseAnnual){

                        }

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
