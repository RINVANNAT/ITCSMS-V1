<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\HighSchool\DataHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\StoreHighSchoolRequest;
use App\Http\Requests\Backend\Configuration\HighSchool\UpdateHighSchoolRequest;
use App\Models\HighSchool;
use App\Models\School;
use App\Repositories\Backend\HighSchool\HighSchoolRepositoryContract;
use Illuminate\Support\Facades\DB;

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
    public function create()
    {
        $highSchools = HighSchool::lists('name_kh','id');
        $schools = School::lists('name_kh','id');
        return view('backend.configuration.highSchool.create',compact('highSchools','schools'));
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
    public function edit($id)
    {
        $highSchool = $this->highSchools->findOrThrowException($id);

        return view('backend.configuration.highSchool.edit',compact('highSchool'));
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
    public function destroy($id)
    {
        $this->highSchools->destroy($id);
        return redirect()->route('admin.configuration.highSchools.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $highSchools = DB::table('highSchools')
            //->whereNull('parent_id')
            ->select(['id','name_kh','name_en','is_no_school']);

        $datatables =  app('datatables')->of($highSchools);


        return $datatables
            ->editColumn('name_kh', '{!! $name_kh !!}')
            ->editColumn('name_en', '{!! $name_en !!}')
            ->editColumn('is_no_school', '{!! str_limit(is_no_school, 200) !!}')
            ->addColumn('action', function ($highSchool) {
                return  '<a href="'.route('admin.configuration.highSchools.edit',$highSchool->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.highSchools.destroy', $highSchool->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
