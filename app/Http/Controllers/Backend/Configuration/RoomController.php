<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Room\CreateRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\DeleteRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\EditRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\ImportRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\RequestImportRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\StoreRoomRequest;
use App\Http\Requests\Backend\Configuration\Room\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Department;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\School;
use App\Repositories\Backend\Room\RoomRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RoomController extends Controller
{
    /**
     * @var RoomRepositoryContract
     */
    protected $rooms;

    /**
     * @param RoomRepositoryContract $roomRepo
     */
    public function __construct(
        RoomRepositoryContract $roomRepo
    )
    {
        $this->rooms = $roomRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.room.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRoomRequest $request)
    {
        $room_types = RoomType::lists('name','id');
        $buildings = Building::lists('name','id');
        $departments = Department::lists('name_kh','id');
        return view('backend.configuration.room.create',compact('room_types','buildings','departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoomRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoomRequest $request)
    {
        $this->rooms->create($request->all());
        return redirect()->route('admin.configuration.rooms.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
    public function edit(EditRoomRequest $request, $id)
    {
        $room_types = RoomType::lists('name','id');
        $buildings = Building::lists('name','id');
        $departments = Department::lists('name_kh','id');

        $room = $this->rooms->findOrThrowException($id);
        return view('backend.configuration.room.edit',compact('room','room_types','buildings','departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoomRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoomRequest $request, $id)
    {
        $this->rooms->update($id, $request->all());
        return redirect()->route('admin.configuration.rooms.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRoomRequest $request, $id)
    {
        if($request->ajax()){
            $this->rooms->destroy($id);
        } else {
            return redirect()->route('admin.configuration.rooms.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $rooms = DB::table('rooms')
            ->join('roomTypes', 'rooms.room_type_id', '=', 'roomTypes.id')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
            ->select([
                'rooms.id as room_id',
                'rooms.name as room_name',
                'nb_desk','nb_chair',
                'nb_chair_exam', 'size',
                'is_exam_room',
                'roomTypes.name as room_type_name',
                'buildings.name as building_name',
                'buildings.code as building_code'
            ]);

        $datatables =  app('datatables')->of($rooms);


        return $datatables
            ->editColumn('rooms.name', function($room){
                return $room->room_name." ".$room->building_code;
            })
            ->editColumn('nb_desk', '{!! $nb_desk !!}')
            ->editColumn('nb_chair', '{!! $nb_chair !!}')
            ->editColumn('nb_chair_exam', '{!! $nb_chair_exam !!}')
            ->editColumn('size', '{!! $size !!}')
            ->editColumn('roomTypes.id', '{!! $room_type_name !!}')
            ->editColumn('buildings.id', '{!! $building_name !!}')
            ->editColumn('is_exam_room', function($room){
                if($room->is_exam_room){
                    return "<i class='glyphicon glyphicon-ok'></i>";
                } else {
                    return "<i class='glyphicon glyphicon-remove text-danger'></i>";
                }
            })
            ->addColumn('action', function ($room) {
                return  '<a href="'.route('admin.configuration.rooms.edit',$room->room_id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.rooms.destroy', $room->room_id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function request_import(RequestImportRoomRequest $request){

        return view('backend.configuration.room.import');

    }

    public function import(ImportRoomRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', "room_".$import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/room_'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(100, function($results){

                    $results->each(function($row) {
                        $room = $this->rooms->create($row->toArray());
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

            return redirect(route('admin.configuration.rooms.index'));
        }
    }

    /*-------------- Room API ----------------*/


}
