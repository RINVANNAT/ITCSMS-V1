<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Building\DataBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\StoreBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\School;
use App\Repositories\Backend\Building\BuildingRepositoryContract;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    /**
     * @var BuildingRepositoryContract
     */
    protected $buildings;

    /**
     * @param BuildingRepositoryContract       $buildings
     */
    public function __construct(
        BuildingRepositoryContract $buildingRepo
    )
    {
        $this->buildings = $buildingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.building.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.configuration.building.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBuildingRequest $request)
    {
        $this->buildings->create($request->all());
        return redirect()->route('admin.configuration.buildings.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $building = $this->buildings->findOrThrowException($id);

        return view('backend.configuration.building.edit',compact('building'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuildingRequest $request, $id)
    {
        $this->buildings->update($id, $request->all());
        return redirect()->route('admin.configuration.buildings.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->buildings->destroy($id);
        return redirect()->route('admin.configuration.buildings.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $buildings = DB::table('buildings')
            //->whereNull('parent_id')
            ->select(['id','name','description']);

        $datatables =  app('datatables')->of($buildings);


        return $datatables
            ->editColumn('id', '{!! str_limit($id, 60) !!}')
            ->editColumn('name', '{!! str_limit($name, 60) !!}')
            ->editColumn('description', '{!! str_limit($description, 200) !!}')
            ->addColumn('action', function ($building) {
                return  '<a href="'.route('admin.configuration.buildings.edit',$building->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.buildings.destroy', $building->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
