<?php

namespace App\Repositories\Backend\IncomeType;


use App\Exceptions\GeneralException;
use App\Models\IncomeType;
use Carbon\Carbon;

/**
 * Class EloquentIncomeTypeRepository
 * @package App\Repositories\Backend\IncomeType
 */
class EloquentIncomeTypeRepository implements IncomeTypeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(IncomeType::find($id))) {
            return IncomeType::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.incomeTypes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getIncomeTypesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return IncomeType::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllIncomeTypes($order_by = 'sort', $sort = 'asc')
    {
        return IncomeType::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (IncomeType::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.incomeTypes.already_exists'));
        }

        $incomeType = new IncomeType();

        $incomeType->name = $input['name'];
        $incomeType->description = $input['description'];
        $incomeType->active = isset($input['active'])?true:false;
        $incomeType->created_at = Carbon::now();
        $incomeType->create_uid = auth()->id();

        if ($incomeType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.incomeTypes.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $incomeType = $this->findOrThrowException($id);

        $incomeType->name = $input['name'];
        $incomeType->description = $input['description'];
        $incomeType->active = isset($input['active'])?true:false;
        $incomeType->updated_at = Carbon::now();
        $incomeType->write_uid = auth()->id();

        if ($incomeType->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.incomeTypes.update_error'));
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
