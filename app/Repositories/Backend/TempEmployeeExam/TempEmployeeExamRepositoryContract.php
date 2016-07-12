<?php

namespace App\Repositories\Backend\TempEmployeeExam;

/**
 * Interface EmployeeRepositoryContract
 * @package App\Repositories\Backend\Employee
 */
interface TempEmployeeExamRepositoryContract
{
    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */

    
    public function getAllStaffWithRoles($order_by='name_kh', $exam_id);


    public function getAllStaffWithoutRoles($exam_id);

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  string  $role
     * @return mixed
     */
    public function getStaffByRole($role_id, $exam_id);

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getRoleBytStaff($staff_id, $exam_id);

    /**
     * @param  integer  $exam_id
     * @return mixed
     */
    public function getAllRoles();

    /**
     * @param  $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param  $id
     * @param  $input
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * @param  $name
     * @return mixed
     */
    public function search($name);
}
