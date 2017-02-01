<?php

namespace App\Repositories\Backend\Department;


use App\Exceptions\GeneralException;
use App\Models\Department;
use App\Repositories\Backend\Department\DepartmentRepositoryContract;
use Carbon\Carbon;

/**
 * Class EloquentRoleRepository
 * @package App\Repositories\Role
 */
class EloquentDepartmentRepository implements DepartmentRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Department::find($id))) {
            return Department::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.departments.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getDepartmentsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Department::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllDepartments($order_by = 'sort', $sort = 'asc')
    {
        return Department::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Department::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.departments.already_exists'));
        }

        $count = Department::count();

        $department = new Department();
        $department->id = intval($count+1);
        $department->name_en = $input['name_en'];
        $department->name_fr = $input['name_fr'];
        $department->name_kh = $input['name_kh'];
        $department->code = $input['code'];
        $department->description = $input['description'];

        $parent_id = intval($input['parent_id']);
        $school_id = intval($input['school_id']);
        if($parent_id ==0)$parent_id = null;
        if($school_id ==0)$school_id = null;

        $department->parent_id = $parent_id;
        $department->school_id = $school_id;
        $department->created_at = Carbon::now();
        $department->create_uid = auth()->id();

        if(isset($input['is_specialist'])){
            $department->is_specialist =  true;
        } else {
            $department->is_specialist =  false;
        }

        if ($department->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.departments.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $department = $this->findOrThrowException($id);

        $department->name_en = $input['name_en'];
        $department->name_fr = $input['name_fr'];
        $department->name_kh = $input['name_kh'];
        $department->code = $input['code'];
        $department->description = $input['description'];
        $department->parent_id = $input['parent_id'];
        $department->school_id = $input['school_id'];
        $department->created_at = Carbon::now();
        $department->create_uid = auth()->id();

        if(isset($input['is_specialist'])){
            $department->is_specialist =  true;
        } else {
            $department->is_specialist =  false;
        }

        if ($department->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.departments.update_error'));
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
