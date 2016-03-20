<?php

namespace App\Repositories\Backend\Employee;


use App\Exceptions\GeneralException;
use App\Models\Employee;
use Carbon\Carbon;

/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\Employee
 */
class EloquentEmployeeRepository implements EmployeeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Employee::find($id))) {
            return Employee::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.candidates.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getEmployeesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Employee::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllEmployees($order_by = 'sort', $sort = 'asc')
    {
        return Employee::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  String $name
     * @return mixed
     */
    public function search($name)
    {
        return Employee::where("name_kh","LIKE","%".$name."%")
            ->orWhere("name_latin", "LIKE", "%".$name."%")
            ->get();
    }


    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $employee = new Employee();

        $employee->name_latin = $input['name_latin'];
        $employee->name_kh = $input['name_kh'];
        $employee->phone = $input['phone'];
        $employee->email = $input['email'];
        $employee->birthdate = $input['birthdate'];
        $employee->address = $input['address'];
        $employee->active = isset($input['active'])?true:false;
        $employee->gender_id = $input['gender_id'];
        $employee->department_id = $input['department_id'];
        if(isset($input['user_id'])){
            $employee->user_id = $input['user_id']==""?null:$input['user_id'];
        }
        $employee->created_at = Carbon::now();
        $employee->create_uid = auth()->id();

        if ($employee->save()) {
            $employee->roles()->sync($input['assignees_roles']);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.candidates.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $employee = $this->findOrThrowException($id);

        $employee->name_latin = $input['name_latin'];
        $employee->name_kh = $input['name_kh'];
        $employee->phone = $input['phone'];
        $employee->email = $input['email'];
        $employee->birthdate = $input['birthdate'];
        $employee->address = $input['address'];
        $employee->active = isset($input['active'])?true:false;
        $employee->gender_id = $input['gender_id'];
        $employee->department_id = $input['department_id'];
        if(isset($input['user_id'])){
            $employee->user_id = $input['user_id']==""?null:$input['user_id'];
        }
        
        $employee->updated_at = Carbon::now();
        $employee->write_uid = auth()->id();

        if ($employee->save()) {
            $employee->roles()->sync($input['assignees_roles']);
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.candidates.update_error'));
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
