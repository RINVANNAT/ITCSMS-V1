<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\Backend\Configuration\AcademicYear\UpdateAcademicYearRequest;
use App\Repositories\Backend\AcademicYear\AcademicYearRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class AcademicYearController extends Controller
{
    /**
     * @var AcademicYearRepositoryContract
     */
    protected $academicYears;

    /**
     * @param AcademicYearRepositoryContract $academicYearRepo
     */
    public function __construct(
        AcademicYearRepositoryContract $academicYearRepo
    )
    {
        $this->academicYears = $academicYearRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.academicYear.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.configuration.academicYear.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $this->academicYears->create($request->all());
        return redirect()->route('admin.configuration.academicYears.index')->withFlashSuccess(trans('alerts.backend.academicYears.created'));
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
        $academicYear = $this->academicYears->findOrThrowException($id);
        return view('backend.configuration.academicYear.edit',compact('academicYear'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAcademicYearRequest $request, $id)
    {
        $this->academicYears->update($id, $request->all());
        return redirect()->route('admin.configuration.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->academicYears->destroy($id);
        return redirect()->route('admin.configuration.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $academicYears = DB::table('academicYears')
            ->select(['id','name_kh','date_start','date_end']);

        $datatables =  app('datatables')->of($academicYears);


        return $datatables
            ->editColumn('id', '{!! str_limit($id, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('date_start', function ($academicYear) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $academicYear->date_start);
                return $date->format('d/m/Y');
            })
            ->editColumn('date_end', function ($academicYear) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $academicYear->date_end);
                return $date->format('d/m/Y');
            })
            ->addColumn('action', function ($academicYear) {

                return  '<a href="'.route('admin.configuration.academicYears.edit',$academicYear->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.

                        ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.academicYears.destroy', $academicYear->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
