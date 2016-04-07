<?php

namespace App\Repositories\Backend\CourseAnnual;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnual;
use Carbon\Carbon;

/**
 * Class EloquentCourseAnnualRepository
 * @package App\Repositories\Backend\CourseAnnual
 */
class EloquentCourseAnnualRepository implements CourseAnnualRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CourseAnnual::find($id))) {
            return CourseAnnual::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseAnnualsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnual::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseAnnuals($order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnual::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (CourseAnnual::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $courseAnnual = new CourseAnnual();

        $courseAnnual->name = $input['name'];
        $courseAnnual->active = isset($input['active'])?true:false;
        $courseAnnual->created_at = Carbon::now();
        $courseAnnual->create_uid = auth()->id();

        if ($courseAnnual->save()) {
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
        $courseAnnual = $this->findOrThrowException($id);

        $courseAnnual->name = $input['name'];
        $courseAnnual->description = $input['description'];
        $courseAnnual->active = isset($input['active'])?true:false;
        $courseAnnual->updated_at = Carbon::now();
        $courseAnnual->write_uid = auth()->id();

        if ($courseAnnual->save()) {
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
