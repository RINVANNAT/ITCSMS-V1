<?php

namespace App\Repositories\Backend\CourseProgram;


use App\Exceptions\GeneralException;
use App\Models\CourseProgram;
use Carbon\Carbon;

/**
 * Class EloquentCourseProgramRepository
 * @package App\Repositories\Backend\CourseProgram
 */
class EloquentCourseProgramRepository implements CourseProgramRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CourseProgram::find($id))) {
            return CourseProgram::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseProgramsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return CourseProgram::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCoursePrograms($order_by = 'sort', $sort = 'asc')
    {
        return CourseProgram::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (CourseProgram::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $courseProgram = new CourseProgram();

        $courseProgram->name = $input['name'];
        $courseProgram->description = $input['description'];
        $courseProgram->active = isset($input['active'])?true:false;
        $courseProgram->created_at = Carbon::now();
        $courseProgram->create_uid = auth()->id();

        if ($courseProgram->save()) {
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
        $courseProgram = $this->findOrThrowException($id);

        $courseProgram->name = $input['name'];
        $courseProgram->description = $input['description'];
        $courseProgram->active = isset($input['active'])?true:false;
        $courseProgram->updated_at = Carbon::now();
        $courseProgram->write_uid = auth()->id();

        if ($courseProgram->save()) {
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
