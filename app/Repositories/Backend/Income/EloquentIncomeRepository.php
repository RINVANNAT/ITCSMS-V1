<?php

namespace App\Repositories\Backend\Income;


use App\Exceptions\GeneralException;
use App\Models\Income;
use Carbon\Carbon;

/**
 * Class EloquentIncomeRepository
 * @package App\Repositories\Backend\Income
 */
class EloquentIncomeRepository implements IncomeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Income::find($id))) {
            return Income::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.incomes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getIncomesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Income::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllIncomes($order_by = 'sort', $sort = 'asc')
    {
        return Income::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Income::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.incomes.already_exists'));
        }

        $income = new Income();

        $income->name = $input['name'];
        $income->description = $input['description'];
        $income->active = isset($input['active'])?true:false;
        $income->created_at = Carbon::now();
        $income->create_uid = auth()->id();

        if ($income->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.incomes.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $income = $this->findOrThrowException($id);

        $income->name = $input['name'];
        $income->description = $input['description'];
        $income->active = isset($input['active'])?true:false;
        $income->updated_at = Carbon::now();
        $income->write_uid = auth()->id();

        if ($income->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.incomes.update_error'));
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
