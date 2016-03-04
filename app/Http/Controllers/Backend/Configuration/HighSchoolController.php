<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\HighSchool\CreateHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\DeleteHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\EditHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\ImportHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\RequestImportHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\StoreHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\UpdateHighSchoolRequest;
use App\Models\Origin;
use App\Repositories\Backend\HighSchool\HighSchoolRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HighSchoolController extends Controller
{
    /**
     * @var HighSchoolRepositoryContract
     */
    protected $highSchools;

    /**
     * @param HighSchoolRepositoryContract       $highSchools
     */
    public function __construct(
        HighSchoolRepositoryContract $highSchoolRepo
    )
    {
        $this->highSchools = $highSchoolRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.highSchool.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateHighSchoolRequest $request)
    {
        $provinces = Origin::where('is_province',true)->lists('name_kh','id');

        return view('backend.configuration.highSchool.create',compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHighSchoolRequest $request)
    {
        $this->highSchools->create($request->all());
        return redirect()->route('admin.configuration.highSchools.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditHighSchoolRequest $request, $id)
    {
        $provinces = Origin::where('is_province',true)->lists('name_kh','id');
        $highSchool = $this->highSchools->findOrThrowException($id);

        return view('backend.configuration.highSchool.edit',compact('highSchool','provinces'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHighSchoolRequest $request, $id)
    {
        $this->highSchools->update($id, $request->all());
        return redirect()->route('admin.configuration.highSchools.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteHighSchoolRequest $request, $id)
    {
        $this->highSchools->destroy($id);
        return redirect()->route('admin.configuration.highSchools.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $highSchools = DB::table('highSchools')
            ->join('origins', 'highSchools.province_id', '=', 'origins.id')
            ->select(['highSchools.id','prefix_id','origins.name_kh as province_name','highSchools.name_kh as highSchool_name_kh','highSchools.name_en as highSchool_name_en','is_no_school']);

        $datatables =  app('datatables')->of($highSchools);


        return $datatables
            ->editColumn('prefix_id', '{!! $prefix_id !!}')
            ->editColumn('highSchools.name_kh', '{!! $highSchool_name_kh !!}')
            ->editColumn('highSchools.name_en', '{!! $highSchool_name_en !!}')
            ->editColumn('province_id', '{!! $province_name !!}')
            ->editColumn('is_no_school', '{!! str_limit($is_no_school, 200) !!}')
            ->addColumn('action', function ($highSchool) {
                //return  '<a href="'.route('admin.configuration.highSchools.edit',$highSchool->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                //' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.highSchools.destroy', $highSchool->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';

                return  '<a href="'.route('admin.configuration.highSchools.edit',$highSchool->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
            })
            ->make(true);
    }

    public function request_import(RequestImportHighSchoolRequest $request){

        return view('backend.configuration.highSchool.import');

    }

    public function import(ImportHighSchoolRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', "highschool_".$import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/highschool_'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(5000, function($results){
                    //dd($results->first());
                    // Loop through all rows
                    $results->each(function($row) {

                        $highSchool = $this->highSchools->create($row->toArray());
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

            return redirect(route('admin.configuration.highSchools.index'));
        }
    }

}
