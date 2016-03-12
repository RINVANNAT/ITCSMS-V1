<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Redouble\CreateRedoubleRequest;
use App\Http\Requests\Backend\Configuration\Redouble\DeleteRedoubleRequest;
use App\Http\Requests\Backend\Configuration\Redouble\EditRedoubleRequest;
use App\Http\Requests\Backend\Configuration\Redouble\StoreRedoubleRequest;
use App\Http\Requests\Backend\Configuration\Redouble\UpdateRedoubleRequest;
use App\Models\Building;
use App\Models\Department;
use App\Models\Redouble;
use App\Models\RedoubleType;
use App\Models\School;
use App\Repositories\Backend\Redouble\RedoubleRepositoryContract;
use Illuminate\Support\Facades\DB;

class RedoubleController extends Controller
{
    /**
     * @var RedoubleRepositoryContract
     */
    protected $redoubles;

    /**
     * @param RedoubleRepositoryContract $redoubleRepo
     */
    public function __construct(
        RedoubleRepositoryContract $redoubleRepo
    )
    {
        $this->redoubles = $redoubleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.redouble.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRedoubleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRedoubleRequest $request)
    {
        return view('backend.configuration.redouble.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRedoubleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRedoubleRequest $request)
    {
        $this->redoubles->create($request->all());
        return redirect()->route('admin.configuration.redoubles.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
     * @param EditRedoubleRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRedoubleRequest $request, $id)
    {

        $redouble = $this->redoubles->findOrThrowException($id);
        return view('backend.configuration.redouble.edit',compact('redouble'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRedoubleRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRedoubleRequest $request, $id)
    {
        $this->redoubles->update($id, $request->all());
        return redirect()->route('admin.configuration.redoubles.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRedoubleRequest $request, $id)
    {
        if($request->ajax()){
            $this->redoubles->destroy($id);
        } else {
            return redirect()->route('admin.configuration.redoubles.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $redoubles = DB::table('redoubles')
            ->select(['id','name_kh','name_en','name_fr','active']);

        $datatables =  app('datatables')->of($redoubles);


        return $datatables
            ->addColumn('action', function ($redouble) {
                return  '<a href="'.route('admin.configuration.redoubles.edit',$redouble->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.redoubles.destroy', $redouble->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
