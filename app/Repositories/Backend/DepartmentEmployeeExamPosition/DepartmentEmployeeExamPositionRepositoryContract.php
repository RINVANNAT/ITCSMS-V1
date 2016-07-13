<?php

namespace App\Repositories\Backend\DepartmentEmployeeExamPosition;

/**
 * Interface EmployeeRepositoryContract
 * @package App\Repositories\Backend\Employee
 */
interface DepartmentEmployeeExamPositionRepositoryContract
{


    public function getAllDepartements($exam_id);

    public function getAllPositionByDepartement($departement_id, $exam_id);

    public function getAllStaffWithoutRoleByPosition($position_id, $role_id=null, $exam_id);

}
