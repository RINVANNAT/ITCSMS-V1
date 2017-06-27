<?php

namespace App\Repositories\Backend\Percentage;


use App\Exceptions\GeneralException;
use App\Models\Percentage;
use App\Models\UserLog;
use Carbon\Carbon;

/**
 * Class EloquentPercentageRepository
 * @package App\Repositories\Backend\Percentage
 */
class EloquentPercentageRepository implements PercentageRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Percentage::find($id))) {
            return Percentage::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.degrees.not_found'));
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllPercentages($order_by = 'sort', $sort = 'asc')
    {
        return Percentage::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $percentage = new Percentage();

        $percentage->name = $input['name'];
        $percentage->percent = $input['percent'];
        $percentage->percentage_type = $input['percentage_type'];

        $percentage->created_at = Carbon::now();
        $percentage->create_uid = auth()->id();

        if ($percentage->save()) {

            UserLog::log([
               'data'=> $percentage,
                'model' => 'Percentage',
                'action' => 'Create'
            ]);
            return $percentage;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.degrees.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $percentage = $this->findOrThrowException($id);

        $percentage->name = isset($input['name'])?$input['name']:$percentage->name;
        $percentage->percent = isset($input['percent'])?$input['percent']:$percentage->percent;
        $percentage->percentage_type = isset($input['percentage_type'])?$input['percentage_type']:$percentage->percentage_type;

        $percentage->updated_at = Carbon::now();
        $percentage->write_uid = auth()->id();

        if ($percentage->save()) {

            UserLog::log([
                'data'=> $percentage,
                'model' => 'Percentage',
                'action' => 'Update'
            ]);

            return $percentage;
        }

        throw new GeneralException(trans('exceptions.configuration.degrees.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);
        if($model) {
            if ($model->delete()) {
                UserLog::log([
                    'data'=> $model,
                    'model' => 'Percentage',
                    'action' => 'Delete'
                ]);

                return true;
            }
        } else {
            return false;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
