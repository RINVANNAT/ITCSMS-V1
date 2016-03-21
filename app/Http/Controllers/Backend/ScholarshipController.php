<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Scholarship\DeleteScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\EditScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\StoreScholarshipRequest;
use App\Http\Requests\Backend\Scholarship\UpdateScholarshipRequest;
use App\Models\Access\User\User;
use App\Models\Department;
use App\Models\Scholarship;
use App\Models\Gender;
use App\Repositories\Backend\Scholarship\ScholarshipRepositoryContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Illuminate\Support\Facades\DB;

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

        return view('backend.scholarship.show',compact('scholarship'));
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

}
