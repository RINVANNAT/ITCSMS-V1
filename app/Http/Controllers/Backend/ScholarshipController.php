<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Scholarship\CreateScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\DeleteScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\EditScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\StoreScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\UpdateScholarshipRequest;
use App\Models\AcademicYear;
use App\Models\Access\User\User;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Grade;
use App\Models\Origin;
use App\Models\Scholarship;
use App\Models\Gender;
use App\Models\StudentAnnual;
use App\Repositories\Backend\Scholarship\ScholarshipRepositoryContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScholarshipController extends Controller
{
    /**
     * @var ScholarshipRepositoryContract
     */
    protected $scholarships;
    protected $roles;

    /**
     * @param ScholarshipRepositoryContract $scholarshipRepo
     * @param RoleRepositoryContract $roleRepo
     */
    public function __construct(
        ScholarshipRepositoryContract $scholarshipRepo,
        RoleRepositoryContract $roleRepo
    )
    {
        $this->scholarships = $scholarshipRepo;
        $this->roles = $roleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.scholarship.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.scholarship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreScholarshipRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScholarshipRequest $request)
    {
        $this->scholarships->create($request->all());
        return redirect()->route('admin.scholarships.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scholarship = $this->scholarships->findOrThrowException($id);
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $options = DepartmentOption::lists('code','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $origins = Origin::lists('name_kh','id');

        return view('backend.scholarship.show',compact('scholarship','departments','degrees','grades','genders','options','academicYears','origins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditScholarshipRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditScholarshipRequest $request, $id)
    {
        $scholarship = $this->scholarships->findOrThrowException($id);

        return view('backend.scholarship.edit',compact('scholarship'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateScholarshipRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScholarshipRequest $request, $id)
    {
        $this->scholarships->update($id, $request->all());
        return redirect()->route('admin.scholarships.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteScholarshipRequest $request, $id)
    {
            $this->scholarships->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
        } else {
            return redirect()->route('admin.scholarships.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $scholarships = DB::table('scholarships')->select(['id','name_kh','name_en','name_fr','code','founder']);

        $datatables =  app('datatables')->of($scholarships);


        return $datatables
            ->addColumn('action', function ($scholarship) {
                return  '<a href="'.route('admin.scholarships.edit',$scholarship->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.scholarships.destroy', $scholarship->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>'.
                ' <a href="'.route('admin.scholarships.show',$scholarship->id).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.view').'"></i> </a>';
            })
            ->make(true);
    }

    public function request_import_holder(CreateScholarshipRequest $request){

        return view('backend.scholarship.import_scholarship_holder');

    }

    public function import_holder(CreateScholarshipRequest $request){
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
                        $scholarshipHolder_data = $row->toArray();

                        $student = StudentAnnual::leftJoin('students','students.id','=','studentAnnuals.student_id')
                            ->where('students.id_card', '=', $scholarshipHolder_data['id_card'])
                            ->where('studentAnnuals.academic_year_id','=',$scholarshipHolder_data['academic_year_id'])
                            ->first();

                        $student->scholarships()->sync([$scholarshipHolder_data['scholarship_id']], false);

                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            /*UserLog
            UserLog::log([
                'model' => 'StudentBac2',
                'action'   => 'Import',
                'data'     => 'none', // if it is create action, store only the new id.
                'developer'   => Auth::id() == 1?true:false
            ]); */

            return view('backend.candidate.popup_success')->withFlashSuccess(trans('alerts.backend.generals.created'));
        }
    }
}
