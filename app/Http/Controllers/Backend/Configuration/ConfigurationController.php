<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\StoreConfigurationRequest;
use App\Http\Requests\Backend\Configuration\UpdateConfigurationRequest;
use App\Models\Configuration;
use App\Repositories\Backend\Configuration\ConfigurationRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
    /**
     * @var ConfigurationRepositoryContract
     */
    protected $configurations;

    /**
     * @param ConfigurationRepositoryContract $configurationRepo
     */
//    public function __construct(
//        ConfigurationRepositoryContract $configurationRepo
//    )
//    {
//        $this->configurations = $configurationRepo;
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.configuration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreConfigurationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConfigurationRequest $request)
    {
        $input = $request->all();

        if (Configuration::where('key', $input['key'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.already_exists'));
        }

        $configuration = new Configuration();

        $configuration->key = $input['key'];
        $configuration->value = $input['value'];
        if(isset($input['description']))$configuration->description = $input['description'];

        $configuration->created_at = Carbon::now();
        $configuration->create_uid = auth()->id();

        if ($configuration->save()) {
            return redirect()->route('admin.configurations.index')->withFlashSuccess(trans('alerts.backend.general.created'));
        }

        throw new GeneralException(trans('exceptions.backend.configuration.create_error'));
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
        $configuration = $this->configurations->findOrThrowException($id);

        return view('backend.configuration.configuration.edit',compact('configuration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateConfigurationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConfigurationRequest $request, $id)
    {
        $this->configurations->update($id, $request->all());
        return redirect()->route('admin.configuration.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->configurations->destroy($id);
        return redirect()->route('admin.configuration.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $configurations = DB::table('configurations')
            ->select(['id','key','value','description']);

        $datatables =  app('datatables')->of($configurations);


        return $datatables
            ->addColumn('action', function ($configuration) {
                return  '<a href="'.route('admin.configuration.configurations.edit',$configuration->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                        ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.configurations.destroy', $configuration->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
