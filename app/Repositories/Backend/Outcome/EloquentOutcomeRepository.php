<?php

namespace App\Repositories\Backend\Outcome;


use App\Exceptions\GeneralException;
use App\Models\Outcome;
use Carbon\Carbon;

/**
 * Class EloquentOutcomeRepository
 * @package App\Repositories\Backend\Outcome
 */
class EloquentOutcomeRepository implements OutcomeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Outcome::find($id))) {
            return Outcome::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.ioutcomes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getOutcomesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Outcome::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllOutcomes($order_by = 'sort', $sort = 'asc')
    {
        return Outcome::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Outcome::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.ioutcomes.already_exists'));
        }

        $ioutcome = new Outcome();

        $ioutcome->name = $input['name'];
        $ioutcome->description = $input['description'];
        $ioutcome->active = isset($input['active'])?true:false;
        $ioutcome->created_at = Carbon::now();
        $ioutcome->create_uid = auth()->id();

        if ($ioutcome->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.ioutcomes.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $ioutcome = $this->findOrThrowException($id);

        $ioutcome->name = $input['name'];
        $ioutcome->description = $input['description'];
        $ioutcome->active = isset($input['active'])?true:false;
        $ioutcome->updated_at = Carbon::now();
        $ioutcome->write_uid = auth()->id();

        if ($ioutcome->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.ioutcomes.update_error'));
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
