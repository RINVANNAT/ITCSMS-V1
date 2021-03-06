<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\RoomType\CreateRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\DeleteRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\EditRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\ImportRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\RequestImportRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\Backend\Configuration\RoomType\UpdateRoomTypeRequest;
use App\Repositories\Backend\RoomType\RoomTypeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RoomTypeController extends Controller
{
    /**
     * @var RoomTypeRepositoryContract
     */
    protected $roomTypes;

    /**
     * @param RoomTypeRepositoryContract $roomTypeRepo
     */
    public function __construct(
        RoomTypeRepositoryContract $roomTypeRepo
    )
    {
        $this->roomTypes = $roomTypeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.roomType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRoomTypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRoomTypeRequest $request)
    {
        return view('backend.configuration.roomType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoomTypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoomTypeRequest $request)
    {
        $this->roomTypes->create($request->all());
        return redirect()->route('admin.configuration.roomTypes.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
     * @param EditRoomTypeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRoomTypeRequest $request, $id)
    {

        $roomType = $this->roomTypes->findOrThrowException($id);
        return view('backend.configuration.roomType.edit',compact('roomType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoomTypeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoomTypeRequest $request, $id)
    {
        $this->roomTypes->update($id, $request->all());
        return redirect()->route('admin.configuration.roomTypes.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRoomTypeRequest $request, $id)
    {
        if($request->ajax()){
            $this->roomTypes->destroy($id);
        } else {
            return redirect()->route('admin.configuration.roomTypes.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $roomTypes = DB::table('roomTypes')
            ->select(['id','name','active']);

        $datatables =  app('datatables')->of($roomTypes);


        return $datatables
            ->addColumn('action', function ($roomType) {
                return  '<a href="'.route('admin.configuration.roomTypes.edit',$roomType->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.roomTypes.destroy', $roomType->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function request_import(RequestImportRoomTypeRequest $request){

        return view('backend.configuration.roomType.import');

    }

    public function import(ImportRoomTypeRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', "roomtype_".$import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/roomtype_'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(100, function($results){

                    $results->each(function($row) {
                        $roomType = $this->roomTypes->create($row->toArray());
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

            return redirect(route('admin.configuration.roomTypes.index'));
        }
    }

}
