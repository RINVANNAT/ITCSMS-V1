<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\CourseAnnual\CreateCourseAnnualRequest;
use App\Http\Requests\Backend\Configuration\CourseProgram\DeleteCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseProgram\EditCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseProgram\StoreCourseProgramRequest;
use App\Http\Requests\Backend\Configuration\CourseProgram\UpdateCourseProgramRequest;
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
        return view('backend.course.courseAnnual.index');
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

    public function data()
    {

        $courseAnnuals = DB::table('courseAnnuals')
            ->select(['id','name','semester','active','academic_year_id','employee_id','department_id','degree_id','grade_id','course_id']);

        $datatables =  app('datatables')->of($courseAnnuals);


        return $datatables
            ->editColumn('name', '{!! $name !!}')
            ->editColumn('semester', '{!! $semester !!}')
            ->editColumn('academic_year_id', '{!! $academic_year_id !!}')
            ->editColumn('department_id', '{!! $department_id !!}')
            ->editColumn('degree_id', '{!! $degree_id !!}')
            ->editColumn('grade_id', '{!! $grade_id !!}')
            ->editColumn('employee_id', '{!! $employee_id !!}')
            ->addColumn('action', function ($courseAnnual) {
                return  '<a href="'.route('admin.course.course_annual.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_annual.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
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
