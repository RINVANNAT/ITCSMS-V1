<?php

namespace App\Repositories\Backend\RoomType;


use App\Exceptions\GeneralException;
use App\Models\RoomType;
use Carbon\Carbon;

/**
 * Class EloquentRoomTypeRepository
 * @package App\Repositories\Backend\RoomType
 */
class EloquentRoomTypeRepository implements RoomTypeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(RoomType::find($id))) {
            return RoomType::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getRoomTypesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return RoomType::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllRoomTypes($order_by = 'sort', $sort = 'asc')
    {
        return RoomType::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (RoomType::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }


        $roomType = new RoomType();
        
        $roomType->name = $input['name'];
        $roomType->active = isset($input['active'])?true:false;

        $roomType->created_at = Carbon::now();
        $roomType->create_uid = auth()->id();

        if ($roomType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $roomType = $this->findOrThrowException($id);

        $roomType->name = $input['name'];
        $roomType->active = isset($input['active'])?true:false;

        $roomType->updated_at = Carbon::now();
        $roomType->write_uid = auth()->id();

        if ($roomType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.update_error'));
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
