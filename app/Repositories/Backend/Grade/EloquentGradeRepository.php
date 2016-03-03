<?php

namespace App\Repositories\Backend\Grade;


use App\Exceptions\GeneralException;
use App\Models\Grade;
use Carbon\Carbon;

/**
 * Class EloquentGradeRepository
 * @package App\Repositories\Backend\Grade
 */
class EloquentGradeRepository implements GradeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Grade::find($id))) {
            return Grade::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.grades.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getGradesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Grade::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllGrades($order_by = 'sort', $sort = 'asc')
    {
        return Grade::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Grade::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.grades.already_exists'));
        }

        $count = Grade::count();

        $grade = new Grade();
        $grade->id = $count+1;
        $grade->name_en = $input['name_en'];
        $grade->name_fr = $input['name_fr'];
        $grade->name_kh = $input['name_kh'];
        $grade->code = $input['code'];
        $grade->description = $input['description'];
        $grade->created_at = Carbon::now();
        $grade->create_uid = auth()->id();

        if ($grade->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.grades.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $grade = $this->findOrThrowException($id);

        $grade->name_en = $input['name_en'];
        $grade->name_fr = $input['name_fr'];
        $grade->name_kh = $input['name_kh'];
        $grade->code = $input['code'];
        $grade->description = $input['description'];
        $grade->created_at = Carbon::now();
        $grade->create_uid = auth()->id();

        if ($grade->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.grades.update_error'));
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
