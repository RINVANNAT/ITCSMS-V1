<?php

namespace App\Repositories\Backend\DepartmentEmployeeExamPosition;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use Carbon\Carbon;
use DB;

/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\DepartementEmployeeExamPosition
 */
class EloquentDepartmentEmployeeExamPositionRepository implements DepartmentEmployeeExamPositionRepositoryContract
{

    public function getAllDepartements($exam_id) {
        echo 'hello me! to get all departement';
    }

    public function getAllPositionByDepartement($departement_id, $exam_id) {
        echo 'hello to get all positions by the departement id ';

    }

    public function getAllStaffWithoutRoleByPosition($position_id, $role_id=null, $exam_id) {
        echo "hello to get all staff without role by the position id ";
    }


}
