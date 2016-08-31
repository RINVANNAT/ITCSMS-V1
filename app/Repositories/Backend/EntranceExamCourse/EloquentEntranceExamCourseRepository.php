<?php

namespace App\Repositories\Backend\EntranceExamCourse;


use App\Exceptions\GeneralException;
use App\Models\EntranceExamCourse;
use App\Models\UserLog;
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

        $entranceExamCourse = EntranceExamCourse::create($input);
        if($entranceExamCourse != null){
            UserLog::log([
                'model' => 'EntranceExamCourse',
                'action'=> 'Create',
                'data'  => $entranceExamCourse->id, // Store only id because we didn't really delete the record
            ]);
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

        $old_record = json_encode($entranceExamCourse);

        $entranceExamCourse->fill($input);
        $entranceExamCourse->updated_at = Carbon::now();
        $entranceExamCourse->write_uid = auth()->id();

        if ($entranceExamCourse->save()) {
            $result["status"] = true;
            $result["messages"] = "Your information is successfully saved";
            UserLog::log([
                'model' => 'EntranceExamCourse',
                'action'=> 'Update',
                'data'  => $old_record
            ]);
        } else {
            $result["status"] = false;
            $result["messages"] = "Something went wrong!";
        }

        return $result;
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);
        $model->active = false;
        $model->write_uid = auth()->id();
        $model->updated_at = Carbon::now();

        if ($model->save()) {
            UserLog::log([
                'model' => 'EntranceExamCourse',
                'action'=> 'Delete',
                'data'  => $id
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
