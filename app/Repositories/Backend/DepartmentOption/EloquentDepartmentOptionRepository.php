<?php

namespace App\Repositories\Backend\DepartmentOption;


use App\Exceptions\GeneralException;
use App\Models\DepartmentOption;
use Carbon\Carbon;

/**
 * Class EloquentDepartmentOptionRepository
 * @package App\Repositories\Backend\DepartmentOption
 */
class EloquentDepartmentOptionRepository implements DepartmentOptionRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(DepartmentOption::find($id))) {
            return DepartmentOption::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.departmentOptions.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getDepartmentOptionsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return DepartmentOption::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllDepartmentOptions($order_by = 'sort', $sort = 'asc')
    {
        return DepartmentOption::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (DepartmentOption::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }


        $departmentOption = new DepartmentOption();
        
        $departmentOption->name_kh = $input['name_kh'];
        $departmentOption->name_en = $input['name_en'];
        $departmentOption->name_fr = $input['name_fr'];
        $departmentOption->code = $input['code'];
        $departmentOption->active = isset($input['active'])?true:false;

        $departmentOption->department_id = $input['department_id'];
        $departmentOption->degree_id = $input['degree_id'];

        $departmentOption->created_at = Carbon::now();
        $departmentOption->create_uid = auth()->id();

        if ($departmentOption->save()) {
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
        $departmentOption = $this->findOrThrowException($id);

        $departmentOption->name_kh = $input['name_kh'];
        $departmentOption->name_en = $input['name_en'];
        $departmentOption->name_fr = $input['name_fr'];
        $departmentOption->code = $input['code'];
        $departmentOption->active = isset($input['active'])?true:false;

        $departmentOption->department_id = $input['department_id'];
        $departmentOption->degree_id = $input['degree_id'];

        $departmentOption->updated_at = Carbon::now();
        $departmentOption->write_uid = auth()->id();

        if ($departmentOption->save()) {
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
