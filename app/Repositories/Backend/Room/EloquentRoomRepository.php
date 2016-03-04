<?php

namespace App\Repositories\Backend\Room;


use App\Exceptions\GeneralException;
use App\Models\Room;
use Carbon\Carbon;

/**
 * Class EloquentRoomRepository
 * @package App\Repositories\Backend\Room
 */
class EloquentRoomRepository implements RoomRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Room::find($id))) {
            return Room::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.rooms.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getRoomsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Room::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllRooms($order_by = 'sort', $sort = 'asc')
    {
        return Room::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Room::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.rooms.already_exists'));
        }


        $room = new Room();
        
        $room->name = $input['name'];
        $room->nb_desk = $input['nb_desk'];
        $room->nb_chair = $input['nb_chair'];
        $room->nb_chair_exam = $input['nb_chair_exam'];
        $room->description = $input['description'];
        $room->size = $input['size'];
        $room->active = isset($input['active'])?true:false;
        $room->room_type_id = $input['room_type_id'];
        $room->department_id = $input['department_id'];
        $room->building_id = $input['building_id'];
        $room->created_at = Carbon::now();
        $room->create_uid = auth()->id();

        if ($room->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.rooms.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $room = $this->findOrThrowException($id);

        $room->name = $input['name'];
        $room->nb_desk = $input['nb_desk'];
        $room->nb_chair = $input['nb_chair'];
        $room->nb_chair_exam = $input['nb_chair_exam'];
        $room->description = $input['description'];
        $room->size = $input['size'];
        $room->active = isset($input['active'])?true:false;
        $room->room_type_id = $input['room_type_id'];
        $room->department_id = $input['department_id'];
        $room->building_id = $input['building_id'];
        $room->updated_at = Carbon::now();
        $room->write_uid = auth()->id();

        if ($room->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.rooms.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);

        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
