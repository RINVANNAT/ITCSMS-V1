<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Building\DataBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\ImportBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\RequestImportBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\StoreBuildingRequest;
use App\Http\Requests\Backend\Configuration\Building\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\School;
use App\Repositories\Backend\Building\BuildingRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BuildingController extends Controller
{
    /**
     * @var BuildingRepositoryContract
     */
    protected $buildings;

    /**
     * @param BuildingRepositoryContract $buildingRepo
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
     * @param  StoreBuildingRequest  $request
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
     * @param  UpdateBuildingRequest  $request
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

        $buildings = DB::table('buildings')
            ->select(['id','name','code','description']);

        $datatables =  app('datatables')->of($buildings);


        return $datatables
            ->addColumn('action', function ($building) {
                return  '<a href="'.route('admin.configuration.buildings.edit',$building->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.buildings.destroy', $building->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function request_import(RequestImportBuildingRequest $request){

        return view('backend.configuration.building.import');

    }

    public function import(ImportBuildingRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', "building_".$import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/building_'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(100, function($results){

                    $results->each(function($row) {
                        $building = $this->buildings->create($row->toArray());
                    });
                });
            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            //UserLog
            /*UserLog::log([
                'model' => 'HighSchool',
                'action'      => 'Import',
                'data'     => 'none', // if it is create action, store only the new id.
                'developer'   => Auth::id() == 1?true:false
            ]);*/

            return redirect(route('admin.configuration.buildings.index'));
        }
    }

}
