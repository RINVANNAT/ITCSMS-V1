<?php

namespace App\Repositories\Backend\TempEmployeeExam;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use App\Models\RoleStaff;
use App\Models\Employee;
use App\Models\TempEmployee;
use Carbon\Carbon;
use DB;

use App\Repositories\Backend\Employee\EloquentEmployeeRepository;
use Illuminate\Support\Facades\Response;

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

        return array($permanentStaff, $temporaryStaff);
    }

    public function getEmployeeHaveRoleIds($exam_id)
    {

        // index: 0 for permanent employee
        //index: 1 for temporary employee

        $permanentEmployeeIds = [];
        $temporaryEmployeeIds = [];

        $temporaryAndPermanentStaffs = $this->getAllEmployeesFromDatabase($exam_id);
        foreach($temporaryAndPermanentStaffs[0] as $permanentStaff) {
            array_push($permanentEmployeeIds, $permanentStaff->id);
        }
        foreach($temporaryAndPermanentStaffs[1] as $temporaryStaff) {
            array_push($temporaryEmployeeIds, $temporaryStaff->id);
        }
        return array( $permanentEmployeeIds, $temporaryEmployeeIds);

    }

    public function getStaffByRole($role_id, $exam_id)
    {

        $allStaffByRoles = [];
        $temporaryStaffByRoles = DB::table('tempEmployees')
            ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('tempEmployees.name_kh', 'tempEmployees.id', 'exams.name', 'roleStaffs.name', 'roleStaffs.id as role_id')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
            ])->get();


        $permanentStaffByRoles = DB::table('employees')
            ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('employees.name_kh', 'employees.id', 'exams.name', 'roleStaffs.name', 'roleStaffs.id as role_id')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
            ])->get();


        foreach ($temporaryStaffByRoles as $temporaryStaffByRole) {
            $element = array(
                "id" => 'tmpstaff_' . $temporaryStaffByRole->role_id . '_' . $temporaryStaffByRole->id,
                "text" => $temporaryStaffByRole->name_kh,
                "children" => false,
                "type" => "staff"
            );

            array_push($allStaffByRoles, $element);
        }

        foreach ($permanentStaffByRoles as $permanentStaffByRole) {
            $element = array(
                "id" => 'perstaff_' . $permanentStaffByRole->role_id . '_' . $permanentStaffByRole->id,
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


    public function createImportedTempEmployees($tempEmployees) {

        $academicYearId = DB::table('academicYears')->where('name_latin', $tempEmployees['academic_year'])->select('id')->first();

        if($academicYearId) {
            if (TempEmployee::where([ ['name_kh', $tempEmployees['name_khmer']], ['academic_year_id', $academicYearId->id] ])->first()) {
                throw new GeneralException(trans('exceptions.backend.general.already_exists'));
//            return ['status'=>false];
            }
        }

        $genderId = DB::table('genders')->where('name_en', $tempEmployees['gender'])->select('id')->first();

        $tempEmployee = new TempEmployee;

        if(isset($tempEmployees['name_khmer']))  $tempEmployee->name_kh = $tempEmployees['name_khmer'];
        if(isset($tempEmployees['name_latin']))$tempEmployee->name_latin = $tempEmployees['name_latin'];
        if(isset($tempEmployees['e_mail']))$tempEmployee->email = $tempEmployees['e_mail'];

        $tempEmployee->active = isset($tempEmployees['active'])?true:false;

        if(isset($tempEmployees['address']))$tempEmployee->address = $tempEmployees['address'];

        if(isset($tempEmployees['phone']))$tempEmployee->phone = $tempEmployees['phone'];

        if(isset($tempEmployees['birth_date'])) $tempEmployee->birthdate =  date('Y-m-d', strtotime($tempEmployees['birth_date']));
        $tempEmployee->gender_id = $genderId->id;
        $tempEmployee->academic_year_id = $academicYearId->id;

        $tempEmployee->created_at = Carbon::now();


        if($tempEmployee->save()){
            return response()->json(['status' => 'import_temp_employee_success',
                'temp_employee_name' => $tempEmployee->name_kh,
                'temp_employee_id' => $tempEmployee->id
            ]);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.rooms.create_error'));

//        dd($tempEmployees);
    }




    public function create($request)
    {
        if (RoleStaff::where('name', $request->role_name)->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }
        $role = new RoleStaff;
        $role->name = $request->role_name;
        $role->description = $request->description;
        $role->save();
        return response()->json(['status' => 'add_role_success',
                                 'role_name' => $role->name,
                                 'role_id' => $role->id
                                ]);
    }

    public function update($id, $request)
    {
        $staffIds = json_decode($request->staff_ids);
        $roleChangeId = $request->role_id;

        foreach ($staffIds as $staffId) {
            $staffRoleIds = explode('_', $staffId);
            if($staffRoleIds[0] == 'perstaff') {
                $employeeId = $staffRoleIds[2];

                $res = DB::table('role_permanent_staff_exams')
                    ->where([
                        ['exam_id', '=', $id],
                        ['employee_id', '=', $employeeId]
                    ])->update(['role_staff_id'=> (int)$roleChangeId]);

            } else {
                $res = [];
            }
            if($staffRoleIds[0] == 'tmpstaff') {
                $tempEmployeeId = $staffRoleIds[2];

                $resTemp = DB::table('role_temporary_staff_exams')
                    ->where([
                        ['exam_id', '=', $id],
                        ['temp_employee_id', '=', $tempEmployeeId]
                    ])->update(['role_staff_id' => $roleChangeId]);
            } else {
                $resTemp = [];
            }
        }

        return $this->checkedResponse($res, $resTemp);
    }

    public function destroy($id,$request)
    {

        $staffIds = json_decode($request->staff_ids);
//        dd($staffIds);
        if($staffIds !==[]) {
            foreach ($staffIds as $staffId) {
                $employeeRoleIds = explode('_', $staffId);

                if ($employeeRoleIds[0] == 'perstaff') {
                    $employeeId = $employeeRoleIds[2];
                    $roleId = $employeeRoleIds[1];
                    $res = DB::table('role_permanent_staff_exams')
                        ->where([
                            ['role_staff_id', '=', $roleId],
                            ['exam_id', '=', $id],
                            ['employee_id', '=', $employeeId]
                        ])->delete();
                } else {
                    $res = [];
                }
                if($employeeRoleIds[0] == 'tmpstaff') {
                    $tempEmployeeId = $employeeRoleIds[2];
                    $roleId = $employeeRoleIds[1];
                    $resTemp = DB::table('role_temporary_staff_exams')
                        ->where([
                            ['role_staff_id', '=', $roleId],
                            ['exam_id', '=', $id],
                            ['temp_employee_id', '=', $tempEmployeeId]
                        ])->delete();
                } else {
                    $resTemp = [];
                }
            }
            return $this->checkedResponse($res, $resTemp);
        }
        return response()->json(['status' => false]);
    }

    public function search($name)
    {

        $val = TempEmployeeExam::where("name_kh", "LIKE", "%" . $name . "%")->orWhere("name_latin", "LIKE", "%" . $name . "%")->get();
        return $val;
    }

    private function checkedResponse($checkedRes,$checkedResTemp) {
        if($checkedRes || $checkedResTemp) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false]);
        }
    }
}
