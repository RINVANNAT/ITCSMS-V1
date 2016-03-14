<?php

namespace App\Repositories\Backend\OutcomeType;


use App\Exceptions\GeneralException;
use App\Models\OutcomeType;
use Carbon\Carbon;

/**
 * Class EloquentOutcomeTypeRepository
 * @package App\Repositories\Backend\Outcome
 */
class EloquentOutcomeTypeRepository implements OutcomeTypeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(OutcomeType::find($id))) {
            return OutcomeType::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.outcomeTypes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getOutcomeTypesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return OutcomeType::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllOutcomeTypes($order_by = 'sort', $sort = 'asc')
    {
        return OutcomeType::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (OutcomeType::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.outcomeTypes.already_exists'));
        }


        $outcomeType = new OutcomeType();

        $outcomeType->code = $input['code'];
        $outcomeType->origin = $input['origin'];
        $outcomeType->name = $input['name'];
        if(isset($input['description'])){
            $outcomeType->description = $input['description'];
        }
        $outcomeType->active = isset($input['active'])?true:false;
        $outcomeType->created_at = Carbon::now();
        $outcomeType->create_uid = auth()->id();

        if ($outcomeType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.outcomeTypes.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $outcomeType = $this->findOrThrowException($id);

        $outcomeType->code = $input['code'];
        $outcomeType->origin = $input['origin'];
        $outcomeType->name = $input['name'];
        $outcomeType->description = $input['description'];
        $outcomeType->active = isset($input['active'])?true:false;
        $outcomeType->updated_at = Carbon::now();
        $outcomeType->write_uid = auth()->id();

        if ($outcomeType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.outcomeTypes.update_error'));
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
