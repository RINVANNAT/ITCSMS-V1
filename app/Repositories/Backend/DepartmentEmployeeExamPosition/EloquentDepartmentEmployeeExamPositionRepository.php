<?php

namespace App\Repositories\Backend\DepartmentEmployeeExamPosition;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use Carbon\Carbon;
use DB;
use App\Repositories\Backend\TempEmployeeExam\EloquentTempEmployeeExamRepository;
use App\Repositories\Backend\Employee\EloquentEmployeeRepository;


/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\DepartementEmployeeExamPosition
 */
class EloquentDepartmentEmployeeExamPositionRepository extends EloquentTempEmployeeExamRepository implements DepartmentEmployeeExamPositionRepositoryContract
{

    public function getAllDepartements($exam_id)
    {
        $allDepartments = [];
        $departments = Department::all();
        foreach ($departments as $department) {
            $element = array(
                "id" => 'department_' . $department->id,
                "text" => $department->name_kh,
                "children" => true,
                "type" => "department"
            );
            array_push($allDepartments, $element);
        }

        return $allDepartments;


    }

    public function getAllPositionByDepartements($department_id, $exam_id)
    {
//        $arrayEmployeeIds = [];
        $arrayPositions = [];

        $employeeWithRoleIds = $this->getEmployeeHaveRoleIds($exam_id);

        $employeePositions = Employee::join('employee_position', 'employees.id', '=', 'employee_position.employee_id')
            ->join('positions', 'positions.id', '=', 'employee_position.position_id')
            ->where('department_id', $department_id)
            ->whereNotIn('employees.id', $employeeWithRoleIds)
            ->select('employees.id', 'employee_position.position_id', 'positions.title')->get();


        foreach ($employeePositions as $employeePosition) {

            $element = array(
                "id" => 'position_' . $department_id . '_' . $employeePosition->position_id,
                "text" => $employeePosition->title,
                "children" => true,
                "type" => "position"
            );
            array_push($arrayPositions, $element);
        }

        $arrayPositions = array_map("unserialize", array_unique(array_map("serialize", $arrayPositions)));

//        dd($arrayPositions);
        return $arrayPositions;

    }

    public function getAllStaffWithoutRoleByPosition($selectedDepartment_id, $position_id, $role_id = null, $exam_id)
    {

        $allStaffWithoutRoles = [];
        $employeeWithRoleIds = $this->getEmployeeHaveRoleIds($exam_id);

        $permanentStaffWithPositions = DB::table('employees')
            ->join('employee_position', 'employees.id', '=', 'employee_position.employee_id')
            ->join('positions', 'positions.id', '=', 'employee_position.position_id')
            ->select('employees.name_kh', 'employees.id', 'employees.department_id', 'positions.title', 'positions.id as position_id')
            ->where([
                ['employee_position.position_id', '=', $position_id],
                ['employees.department_id', '=', $selectedDepartment_id]
            ])
            ->whereNotIn('employees.id', $employeeWithRoleIds)
            ->get();

        foreach ($permanentStaffWithPositions as $permanentStaffWithPosition) {

            $element = array(
                "id" => 'staffbyposition_' . $selectedDepartment_id . '_' . $permanentStaffWithPosition->position_id . '_' . $permanentStaffWithPosition->id,
                "text" => $permanentStaffWithPosition->name_kh,
                "children" => false,
                "type" => "staff"
            );

            array_push($allStaffWithoutRoles, $element);
        }

        return ($allStaffWithoutRoles);

    }

    public function saveStaffForEachRole($id, $request)
    {
        $employeeRepo = new EloquentEmployeeRepository;

        $staff_ids = json_decode($request->staff_ids);

        foreach ($staff_ids as $staff_id) {
            $employeePositionIds = explode('_', $staff_id);

            if ($employeePositionIds[0] == 'staffbyposition') {

                $positionId = (int)$employeePositionIds[2];
                $employeeId = (int)$employeePositionIds[3];

                $resEmployeeRole = DB::table('role_permanent_staff_exams')->insert([
                    ['role_staff_id' => $request->role_id, 'exam_id' => $id, 'employee_id' => $employeeId]
                ]);

                $employees = $employeeRepo->findOrThrowException($employeeId);
                $resEmployeePosition = $employees->positions()->sync([$positionId],false);

            }
        }
        if ($resEmployeeRole && $resEmployeePosition) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'fail']);
        }

    }


}
