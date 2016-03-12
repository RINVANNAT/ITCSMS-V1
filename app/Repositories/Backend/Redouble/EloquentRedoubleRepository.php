<?php

namespace App\Repositories\Backend\Redouble;


use App\Exceptions\GeneralException;
use App\Models\Redouble;
use Carbon\Carbon;

/**
 * Class EloquentRedoubleRepository
 * @package App\Repositories\Backend\Redouble
 */
class EloquentRedoubleRepository implements RedoubleRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Redouble::find($id))) {
            return Redouble::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getRedoublesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Redouble::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllRedoubles($order_by = 'sort', $sort = 'asc')
    {
        return Redouble::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Redouble::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }


        $redouble = new Redouble();
        
        $redouble->name_en = $input['name_en'];
        $redouble->name_kh = $input['name_kh'];
        $redouble->name_fr = $input['name_fr'];
        $redouble->active = isset($input['active'])?true:false;

        $redouble->created_at = Carbon::now();
        $redouble->create_uid = auth()->id();

        if ($redouble->save()) {
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
        $redouble = $this->findOrThrowException($id);

        $redouble->name_en = $input['name_en'];
        $redouble->name_kh = $input['name_kh'];
        $redouble->name_fr = $input['name_fr'];
        $redouble->active = isset($input['active'])?true:false;

        $redouble->updated_at = Carbon::now();
        $redouble->write_uid = auth()->id();

        if ($redouble->save()) {
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
