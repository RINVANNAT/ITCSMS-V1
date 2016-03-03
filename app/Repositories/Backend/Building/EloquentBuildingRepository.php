<?php

namespace App\Repositories\Backend\Building;


use App\Exceptions\GeneralException;
use App\Models\Building;
use Carbon\Carbon;

/**
 * Class EloquentBuildingRepository
 * @package App\Repositories\Backend\Building
 */
class EloquentBuildingRepository implements BuildingRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Building::find($id))) {
            return Building::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.buildings.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getBuildingsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Building::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllBuildings($order_by = 'sort', $sort = 'asc', $withPermissions = false)
    {
        return Building::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Building::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.buildings.already_exists'));
        }

        $count = Building::count();
        $building = new Building();

        $building->id = $count+1;
        $building->name = $input['name'];
        $building->description = $input['description'];
        $building->created_at = Carbon::now();
        $building->create_uid = auth()->id();

        if ($building->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.buildings.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $building = $this->findOrThrowException($id);

        $building->name = $input['name'];
        $building->description = $input['description'];
        $building->updated_at = Carbon::now();
        $building->write_uid = auth()->id();

        if ($building->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.buildings.update_error'));
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
