<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\DepartmentOption\CreateDepartmentOptionRequest;
use App\Http\Requests\Backend\Configuration\DepartmentOption\DeleteDepartmentOptionRequest;
use App\Http\Requests\Backend\Configuration\DepartmentOption\EditDepartmentOptionRequest;
use App\Http\Requests\Backend\Configuration\DepartmentOption\StoreDepartmentOptionRequest;
use App\Http\Requests\Backend\Configuration\DepartmentOption\UpdateDepartmentOptionRequest;
use App\Models\Building;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\DepartmentOptionType;
use App\Models\School;
use App\Repositories\Backend\DepartmentOption\DepartmentOptionRepositoryContract;
use Illuminate\Support\Facades\DB;

class DepartmentOptionController extends Controller
{
    /**
     * @var DepartmentOptionRepositoryContract
     */
    protected $departmentOptions;

    /**
     * @param DepartmentOptionRepositoryContract $departmentOptionRepo
     */
    public function __construct(
        DepartmentOptionRepositoryContract $departmentOptionRepo
    )
    {
        $this->departmentOptions = $departmentOptionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.departmentOption.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateDepartmentOptionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateDepartmentOptionRequest $request)
    {
        $departments = Department::where('parent_id',11)->lists('name_kh','id');
        $degrees = Degree::lists('name_kh','id');
        return view('backend.configuration.departmentOption.create',compact('degrees','departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDepartmentOptionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartmentOptionRequest $request)
    {
        $this->departmentOptions->create($request->all());
        return redirect()->route('admin.configuration.departmentOptions.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
     * @param EditDepartmentOptionRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditDepartmentOptionRequest $request, $id)
    {

        $departments = Department::where('parent_id',11)->lists('name_kh','id');
        $degrees = Degree::lists('name_kh','id');

        $departmentOption = $this->departmentOptions->findOrThrowException($id);
        return view('backend.configuration.departmentOption.edit',compact('departmentOption','degrees','departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDepartmentOptionRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartmentOptionRequest $request, $id)
    {
        $this->departmentOptions->update($id, $request->all());
        return redirect()->route('admin.configuration.departmentOptions.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteDepartmentOptionRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteDepartmentOptionRequest $request, $id)
    {
        if($request->ajax()){
            $this->departmentOptions->destroy($id);
        } else {
            return redirect()->route('admin.configuration.departmentOptions.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $departmentOptions = DB::table('departmentOptions')
            ->leftJoin('departments', 'departmentOptions.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'departmentOptions.degree_id', '=', 'degrees.id')
            ->select(['departmentOptions.id as id','departmentOptions.name_kh as option_name_kh','departmentOptions.code as option_code',
                'departmentOptions.name_en as option_name_en','departmentOptions.name_fr as option_name_fr', 'departments.code as department_code', 'degrees.name_kh as degree_name_kh']);

        $datatables =  app('datatables')->of($departmentOptions)

            ->addColumn('action', function ($departmentOption) {
                return  '<a href="'.route('admin.configuration.departmentOptions.edit',$departmentOption->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.departmentOptions.destroy', $departmentOption->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            });

            return $datatables->make(true);
    }

}
