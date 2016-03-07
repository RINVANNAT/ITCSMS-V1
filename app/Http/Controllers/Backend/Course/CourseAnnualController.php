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
                return  '<a href="'.route('admin.course.courseAnnuals.edit',$courseAnnual->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.courseAnnuals.destroy', $courseAnnual->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
