<?php namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseProgram\CreateCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\DeleteCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\EditCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\StoreCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\UpdateCourseProgramRequest;
use App\Repositories\Backend\CourseProgram\CourseProgramRepositoryContract;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\Course\CourseProgram\ImportCourseProgramRequest;
use App\Http\Requests\Backend\Course\CourseProgram\RequestImportCourseProgramRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Course;
use Illuminate\Foundation\Validation\ValidatesRequests;




class CourseController extends Controller
{
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
        return view('backend.course.courseProgram.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCourseProgramRequest $request)
    {
        return view('backend.course.courseProgram.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseProgramRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseProgramRequest $request)
    {
        $this->validate($request, Course::$rules);
        $this->coursePrograms->create($request->all());
        return redirect()->route('admin.course.course_program.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
        $courseProgram = $this->coursePrograms->findOrThrowException($id);

        return view('backend.course.courseProgram.edit',compact('courseProgram'));
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
        $this->coursePrograms->update($id, $request->all());
        return redirect()->route('admin.course.coursePrograms.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
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
        $this->coursePrograms->destroy($id);
        if($request->ajax()){
            return json_encode(array('success'=>'true'));
        } else {
            return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $coursePrograms = DB::table('courses')
            ->select(['id','name_kh','name_en','name_fr','code','time_tp','time_td','time_course']);

        $datatables =  app('datatables')->of($coursePrograms);


        return $datatables
            ->editColumn('name_kh', '{!! $name_kh !!}')
            ->editColumn('name_en', '{!! $name_en !!}')
            ->editColumn('name_fr', '{!! $name_fr !!}')
            ->editColumn('code', '{!! $code !!}')
            ->editColumn('duration', '{!! $time_course."/".$time_tp."/".$time_td !!}')

            ->addColumn('action', function ($courseProgram) {
                return  '<a href="'.route('admin.course.course_program.edit',$courseProgram->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.course.course_program.destroy', $courseProgram->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }




    public function request_import(RequestImportCourseProgramRequest $request){

        return view('backend.course.courseProgram.import');

    }

    public function import(ImportCourseProgramRequest $request){

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
                        $courseAnnual = Course::create($courseAnnual_data);


                        if(  $courseAnnual){

                        }

                        $first = false;
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();
            return redirect(route('admin.course.coursePrograms.index'));
        }
    }





}
