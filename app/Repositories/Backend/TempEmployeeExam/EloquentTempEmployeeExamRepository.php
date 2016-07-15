<?php

namespace App\Repositories\Backend\TempEmployeeExam;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use App\Models\RoleStaff;
use App\Models\Employee;
use Carbon\Carbon;
use DB;

/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\Employee
 */
class EloquentTempEmployeeExamRepository implements TempEmployeeExamRepositoryContract
{

    public function getAllStaffWithRoles($order_by = 'name_kh', $exam_id)
    {
        $staffs = $this->getAllEmployeesFromDatabase($exam_id);
        $temporaryStaff = $staffs[0];
        $permanentStaff = $staffs[1];
        $allStaffs = [];
        foreach ($temporaryStaff as $staff) {

            $element = array(
                "id" => 'tmpstaff_' . $staff->id,
                "text" => $staff->name_kh,
                "children" => true,
                "type" => "staff"
            );
            array_push($allStaffs, $element);

        }
        foreach ($permanentStaff as $perStaff) {

            $element = array(
                "id" => 'perstaff_' . $perStaff->id,
                "text" => $perStaff->name_kh,
                "children" => true,
                "type" => "staff"
            );
            array_push($allStaffs, $element);
        }

        return $allStaffs;

    }

    private function getAllEmployeesFromDatabase($exam_id)
    {
        $temporaryStaff = DB::table('tempEmployees')
            ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('tempEmployees.name_kh', 'tempEmployees.id', 'exams.name', 'roleStaffs.name as role_name', 'roleStaffs.id as role_id')
            ->where('exams.id', '=', $exam_id)->get();

        $permanentStaff = DB::table('employees')
            ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('employees.name_kh', 'employees.id', 'exams.name', 'roleStaffs.name as role_name', 'roleStaffs.id as role_id')
            ->where('exams.id', '=', $exam_id)->get();

        return array($temporaryStaff,$permanentStaff);
    }

    public function getEmployeeHaveRoleIds($exam_id)
    {

        $allStaffWithoutRoles = [];
        $employeeIds = [];

        $temporaryAndPermanentStaffs = $this->getAllEmployeesFromDatabase($exam_id);
        foreach($temporaryAndPermanentStaffs[1] as $permanentStaff) {
            array_push($employeeIds, $permanentStaff->id);
        }
        return $employeeIds;

    }

    public function getStaffByRole($role_id, $exam_id)
    {

        $allStaffByRoles = [];
        $temporaryStaffByRoles = DB::table('tempEmployees')
            ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('tempEmployees.name_kh', 'tempEmployees.id', 'exams.name', 'roleStaffs.name')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
            ])->get();


        $permanentStaffByRoles = DB::table('employees')
            ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('employees.name_kh', 'employees.id', 'exams.name', 'roleStaffs.name')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
            ])->get();


        foreach ($temporaryStaffByRoles as $temporaryStaffByRole) {
            $element = array(
                "id" => 'tmpstaff_' . $temporaryStaffByRole->id,
                "text" => $temporaryStaffByRole->name_kh,
                "children" => false,
                "type" => "staff"
            );

            array_push($allStaffByRoles, $element);
        }

        foreach ($permanentStaffByRoles as $permanentStaffByRole) {
            $element = array(
                "id" => 'perstaff_' . $permanentStaffByRole->id,
                "text" => $permanentStaffByRole->name_kh,
                "children" => false,
                "type" => "staff"
            );

            array_push($allStaffByRoles, $element);
        }
        return $allStaffByRoles;

    }

    public function getRoleBytStaff($staff_id, $exam_id)
    {

        $roleTemporary = DB::table('roleStaffs')
            ->join('role_temporary_staff_exams', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('tempEmployees', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('roleStaffs.name')
            ->where([
                ['tempEmployees.id', '=', $staff_id],
                ['exams.id', '=', $exam_id],
            ])->get();

        $rolePermanent = DB::table('roleStaffs')
            ->join('role_permanent_staff_exams', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('employees', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('roleStaffs.name')
            ->where([
                ['employees.id', '=', $staff_id],
                ['exams.id', '=', $exam_id],
            ])->get();

        if ($roleTemporary != null && $rolePermanent == null) {
            return $roleTemporary;
        } else if ($roleTemporary == null && $rolePermanent != null) {
            return $rolePermanent;
        } else {
            return ['this is null'];
        }

    }

    public function getAllRoles($exam_id)
    {

        $roles = [];
        $roleInExam = $this->getAllEmployeesFromDatabase($exam_id);
        $roleTempStaffs = $roleInExam[0];
        $rolePerStaffs = $roleInExam[1];
//        dd($roleInExam);
        foreach ($roleTempStaffs as $roleTempStaff) {
            $element = array(
                "id" => 'role_' . $roleTempStaff->role_id,
                "text" => $roleTempStaff->role_name,
                "children" => true,
                "type" => "role"
            );

            array_push($roles, $element);
        }

        foreach ($rolePerStaffs as $rolePerStaff) {
            $element = array(
                "id" => 'role_' . $rolePerStaff->role_id,
                "text" => $rolePerStaff->role_name,
                "children" => true,
                "type" => "role"
            );

            array_push($roles, $element);
        }


        $roles = array_map("unserialize", array_unique(array_map("serialize", $roles)));
        //$roles = array_unique($roles, SORT_REGULAR);

        return $roles;

    }

    public function getRoles()
    {

        $roles = RoleStaff::all();

        return ($roles);

    }

    public function create($request)
    {


        $res = DB::table('roleStaffs')->insert([
            [ 'name' => $request->role_name, 'description' => $request->description]
        ]);
        if($res){
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function update($id, $request)
    {

        echo 'hello update';
    }

    public function destroy($id)
    {

        echo 'hello destroy';
    }

    public function search($name)
    {

        $val = TempEmployeeExam::where("name_kh", "LIKE", "%" . $name . "%")->orWhere("name_latin", "LIKE", "%" . $name . "%")->get();
        return $val;
    }
}
