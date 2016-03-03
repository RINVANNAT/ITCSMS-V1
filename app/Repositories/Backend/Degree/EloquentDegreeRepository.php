<?php

namespace App\Repositories\Backend\Degree;


use App\Exceptions\GeneralException;
use App\Models\Degree;
use Carbon\Carbon;

/**
 * Class EloquentDegreeRepository
 * @package App\Repositories\Backend\Degree
 */
class EloquentDegreeRepository implements DegreeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Degree::find($id))) {
            return Degree::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.degrees.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getDegreesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Degree::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllDegrees($order_by = 'sort', $sort = 'asc')
    {
        return Degree::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Degree::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.degrees.already_exists'));
        }

        $count = Degree::count();

        $degree = new Degree();
        $degree->id = $count+1;
        $degree->name_en = $input['name_en'];
        $degree->name_fr = $input['name_fr'];
        $degree->name_kh = $input['name_kh'];
        $degree->code = $input['code'];
        $degree->school_id = $input['school_id'];
        $degree->description = $input['description'];
        $degree->created_at = Carbon::now();
        $degree->create_uid = auth()->id();

        if ($degree->save()) {
            if(isset($input['departments'])){
                $departmentIds = $input['departments'];
                $degree->departments()->sync($departmentIds);
            }
            return true;
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
        $degree = $this->findOrThrowException($id);

        $degree->name_en = $input['name_en'];
        $degree->name_fr = $input['name_fr'];
        $degree->name_kh = $input['name_kh'];
        $degree->code = $input['code'];
        $degree->school_id = $input['school_id'];
        $degree->description = $input['description'];
        $degree->updated_at = Carbon::now();
        $degree->write_uid = auth()->id();

        if ($degree->save()) {
            if(isset($input['departments'])){
                $departmentIds = $input['departments'];
                $degree->departments()->sync($departmentIds);
            }
            return true;
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

        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
