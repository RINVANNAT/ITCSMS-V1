<?php

namespace App\Repositories\Backend\EntranceExamCourse;


use App\Exceptions\GeneralException;
use App\Models\EntranceExamCourse;
use Carbon\Carbon;

/**
 * Class EloquentEntranceExamCourseRepository
 * @package App\Repositories\Backend\EntranceExamCourse
 */
class EloquentEntranceExamCourseRepository implements EntranceExamCourseRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(EntranceExamCourse::find($id))) {
            return EntranceExamCourse::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getEntranceExamCoursesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return EntranceExamCourse::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllEntranceExamCourses($order_by = 'sort', $sort = 'asc')
    {
        return EntranceExamCourse::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $input['create_uid'] = auth()->id();
        $input['created_at'] = Carbon::now();

        if(EntranceExamCourse::create($input)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $entranceExamCourse = $this->findOrThrowException($id);

        $entranceExamCourse->name = $input['name'];
        $entranceExamCourse->description = $input['description'];
        $entranceExamCourse->active = isset($input['active'])?true:false;
        $entranceExamCourse->updated_at = Carbon::now();
        $entranceExamCourse->write_uid = auth()->id();

        if ($entranceExamCourse->save()) {
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
