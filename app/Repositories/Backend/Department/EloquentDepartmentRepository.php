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
     * @param  bool $withPermissions
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id, $withPermissions = false)
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
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllDepartments($order_by = 'sort', $sort = 'asc', $withPermissions = false)
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
        $department->id = $count+1;
        $department->name_en = $input['name_en'];
        $department->name_fr = $input['name_fr'];
        $department->name_kh = $input['name_kh'];
        $department->code = $input['code'];
        $department->description = $input['description'];
        $department->is_specialist = $input['is_specialist'];
        $department->parent_id = $input['parent_id'];
        $department->school_id = $input['school_id'];
        $department->created_at = Carbon::now();
        $department->create_uid = auth()->id();

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

        if ($department->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.departments.update_error'));
    }


}
